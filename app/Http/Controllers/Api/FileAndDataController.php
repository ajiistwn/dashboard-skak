<?php

namespace App\Http\Controllers\Api;

use App\Models\FileAndData;
use Illuminate\Http\Request;
use App\Models\CategoryDocument;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\Project; // Assuming you have a Project model
use Illuminate\Auth\Events\Validated;

class FileAndDataController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         // Validasi input
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id', // ID project yang ingin difilter
            'filters' => 'nullable|array', // Array of filters (e.g., [1 => true, 2 => false])
            'search' => 'nullable|string', // Search query (e.g., "Crew" or "Legal")
        ]);

        $categoryMapping = [
            'legal' => 1,
            'finance' => 2,
            'businessDevelopment' => 3,
            'ipDevelopment' => 4,
            'preProduction' => 5,
            'production' => 6,
            'postProduction' => 7,
            'promo' => 8,
        ];

        $activeCategoryIds = [];
        foreach ($validated['filters'] ?? [] as $categoryName) {
            if (isset($categoryMapping[$categoryName])) {
                $activeCategoryIds[] = $categoryMapping[$categoryName];
            }
        }

        $filesAndDatas = FileAndData::with('categoryDocument')
            ->select('id', 'name', 'file_source', 'created_at', 'image', 'category_documents_id')->where('project_id', $validated['project_id']);



        //   // Filter berdasarkan id_category_document
        if (!empty($activeCategoryIds)) {
            $filesAndDatas->whereIn('category_documents_id', $activeCategoryIds);
        }

        // // Search berdasarkan nama file atau nama kategori dokumen
        if (!empty($validated['search'])) {
            $filesAndDatas->where(function ($q) use ($validated) {
                $q->where('name', 'like', '%' . $validated['search'] . '%') // Cari di kolom nama file
                ->orWhereHas('categoryDocument', function ($q) use ($validated) {
                    $q->where('name', 'like', '%' . $validated['search'] . '%'); // Cari di nama kategori dokumen
                });
            });
        }

        $datas = $filesAndDatas->get()->groupBy('categoryDocument.name'); // Kelompokkan berdasarkan category_documents_id;

        return response()->json($datas);
    }

    public function getCategoryDocuments()
    {
        $categories = CategoryDocument::select('id', 'name')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'file_source' => 'nullable|url|string',
            'category_documents_id' => 'exists:category_documents,id',
            'project_id' => 'nullable|exists:projects,id',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            // Buat nama file dengan format nama-project_nama-field_timestamp_hash.extension
            $projectName = $request->project_id ? Project::find($request->project_id)->name : 'no_project';
            $projectSlug = Str::slug($projectName, '-'); // Slugify nama project
            $fileName = Str::slug($validated['name'], '-'); // Slugify nama file
            $fieldName = 'image';
            $timestamp = time(); // Waktu saat ini
            $hash = substr(md5($image->getClientOriginalName()), 0, 6); // Hash pendek dari nama file asli
            $extension = $image->getClientOriginalExtension(); // Ekstensi file

            // Gabungkan semua komponen untuk membuat nama file
            $imageName = "{$projectSlug}_{$fieldName}_{$fileName}_{$timestamp}_{$hash}.{$extension}";

            // Path folder dan file
            $folderPath = 'uploads/file_and_data'; // Nama folder di storage
            $imagePath = $folderPath . '/' . $imageName;

            // Pastikan folder ada, jika tidak, buat otomatis
            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            // Simpan file ke storage
            Storage::disk('public')->put($imagePath, file_get_contents($image));

            // URL absolut
            $imageUrl = url('storage/' . $imagePath);

            // Masukkan ke validated data
            $validated['image'] = $imageUrl;
        }

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Buat nama file dengan format nama-project_nama-field_timestamp_hash.extension
            $projectName = $request->project_id ? Project::find($request->project_id)->name : 'no_project';
            $projectSlug = Str::slug($projectName, '-'); // Slugify nama project
            $fieldName = 'file';
            $timestamp = time(); // Waktu saat ini
            $fileName = Str::slug($validated['name'], '-'); // Slugify nama file
            $hash = substr(md5($file->getClientOriginalName()), 0, 6); // Hash pendek dari nama file asli
            $extension = $file->getClientOriginalExtension(); // Ekstensi file

            // Gabungkan semua komponen untuk membuat nama file
            $fileName = "{$projectSlug}_{$fieldName}_{$fileName}_{$timestamp}_{$hash}.{$extension}";

            // Path folder dan file
            $folderPath = 'uploads/file_and_data'; // Nama folder di storage
            $filePath = $folderPath . '/' . $fileName;

            // Pastikan folder ada, jika tidak, buat otomatis
            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            // Simpan file ke storage
            Storage::disk('public')->put($filePath, file_get_contents($file));

            // URL absolut
            $fileUrl = url('storage/' . $filePath);

            // Masukkan ke validated data
            $validated['file'] = $fileUrl;
        }

        // Simpan ke database
        $fileAndData = FileAndData::create($validated);

        return response()->json($fileAndData);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $fileAndData = FileAndData::with(['categoryDocument' => function ($query) {
            $query->select('id', 'name'); // Ambil hanya kolom id dan name dari categoryDocument
        }])->find($id);

        if (!$fileAndData) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        return response()->json($fileAndData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Cari data berdasarkan ID
        $fileAndData = FileAndData::find($id);

        if (!$fileAndData) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        // Validasi input
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255', // Nama harus berupa string maksimal 255 karakter
            'file_source' => 'nullable|url|string', // Sumber file harus berupa URL atau boleh kosong
            'category_documents_id' => 'nullable|exists:category_documents,id', // ID kategori dokumen harus ada di database atau boleh kosong
            'project_id' => 'nullable|exists:projects,id', // ID proyek harus ada di database atau boleh kosong
            'image' => 'nullable', // Gambar harus berupa file dengan format tertentu dan ukuran maksimal 2MB
            'file' => 'nullable', // File harus berupa PDF/DOC/XLS dengan ukuran maksimal 10MB
        ]);

        $projectName = $validated['project_id'] ? Project::find($request->project_id)->name : 'no_project'; // Ambil nama proyek (jika ada)
        $projectSlug = Str::slug($projectName, '-'); // Ubah nama proyek menjadi slug

        // Cek apakah ada file gambar baru
        if ($request->hasFile('image')) {
            // Jika ada file gambar baru, simpan gambar baru dan hapus gambar lama
            if ($fileAndData->image) {
                $relativePathImage = $this->extractRelativePathFromUrl($fileAndData->image);
                Storage::disk('public')->delete($relativePathImage);
            }

            // Simpan file gambar baru ke storage
            $fileImage = $request->file('image');
            $nameSlugImage = Str::slug($validated['name'], '-'); // Slugify nick_name
            $fileNameFieldImage = "image"; // Nama file berdasarkan slug nama
            $timestampImage = time(); // Waktu saat ini
            $hashImage = substr(md5($fileImage->getClientOriginalName()), 0, 6); // Hash pendek dari nama file asli
            $extensionImage = $fileImage->getClientOriginalExtension(); // Ekstensi file
            $fileNameImage = "{$projectSlug}_{$fileNameFieldImage}_{$nameSlugImage}_{$timestampImage}_{$hashImage}.{$extensionImage}";

            // Path folder dan file
            $folderPathImage = 'uploads/file_and_data'; // Nama folder di storage
            $filePathImage = $folderPathImage . '/' . $fileNameImage;

            // Pastikan folder ada, jika tidak, buat otomatis
            if (!Storage::disk('public')->exists($folderPathImage)) {
                Storage::disk('public')->makeDirectory($folderPathImage);
            }

            // Simpan file ke storage
            Storage::disk('public')->put($filePathImage, file_get_contents($fileImage));

            // URL absolut
            $imageUrl = url('storage/' . $filePathImage);

            // Masukkan URL gambar baru ke validated data
            $validated['image'] = $imageUrl;
        } elseif ($request->input('image') && $request->input('image') === $fileAndData->image) {
            // $hasil = "oke";
            // Jika image adalah URL string dan sama dengan yang ada di database, tidak perlu diubah
            unset($validated['image']); // Hapus dari validated data agar tidak di-update
        } elseif ($request->input('image') === null) {
            // Jika request image adalah null, hapus gambar lama dan file-nya
            if ($fileAndData->image) {
                $relativePathImage = $this->extractRelativePathFromUrl($fileAndData->image);
                Storage::disk('public')->delete($relativePathImage);
            }
            $validated['image'] = null; // Set image menjadi null
        }

         // Penanganan File
        if ($request->hasFile('file')) {
            // Jika ada file baru yang diunggah, cek apakah berbeda dengan file yang sudah ada
            if ($fileAndData->file) {
                $relativePathFile = $this->extractRelativePathFromUrl($fileAndData->file);
                Storage::disk('public')->delete($relativePathFile);
            }

            // Simpan file baru ke storage
            $fileFile = $request->file('file');
            $fieldNameFile = "file"; // Nama field untuk file
            $nameSlugFIle = Str::slug($validated['name'], '-');
            $timestampFile = time(); // Waktu saat ini
            $hashFile = substr(md5($fileFile->getClientOriginalName()), 0, 6); // Hash pendek dari nama file asli
            $extensionFile = $fileFile->getClientOriginalExtension(); // Ekstensi file
            $fileNameFile = "{$projectSlug}_{$fieldNameFile}_{$nameSlugFIle}_{$timestampFile}_{$hashFile}.{$extensionFile}";

            // Path folder dan file
            $folderPathFile = 'uploads/file_and_data'; // Nama folder di storage
            $filePathFile = $folderPathFile . '/' . $fileNameFile;

            // Pastikan folder ada, jika tidak, buat otomatis
            if (!Storage::disk('public')->exists($folderPathFile)) {
                Storage::disk('public')->makeDirectory($folderPathFile);
            }

            // Simpan file ke storage
            Storage::disk('public')->put($filePathFile, file_get_contents($fileFile));

            // URL absolut
            $fileUrlFile = url('storage/' . $filePathFile);

            // Masukkan URL file baru ke validated data
            $validated['file'] = $fileUrlFile;
        } elseif ($request->input('file') && $request->input('file') === $fileAndData->file) {
            // Jika file adalah URL string dan sama dengan yang ada di database, tidak perlu diubah
            unset($validated['file']); // Hapus dari validated data agar tidak di-update
        } elseif ($request->input('file') === null) {
            // Jika request file adalah null, hapus file lama dan file-nya
            if ($fileAndData->file) {
                $relativeFilePath = $this->extractRelativePathFromUrl($fileAndData->file);
                Storage::disk('public')->delete($relativeFilePath);
            }
            $validated['file'] = null; // Set file menjadi null
        }

        // Update data di database
        $fileAndData->update($validated);



        return response()->json($fileAndData);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $fileAndData = FileAndData::find($id);

        if (!$fileAndData) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // Hapus file terkait dari storage
        if ($fileAndData->image) {
            $relativePathImage = $this->extractRelativePathFromUrl($fileAndData->image);
            Storage::disk('public')->delete($relativePathImage); // Gunakan relative path
        }
        if ($fileAndData->file) {
            $relativePathFile = $this->extractRelativePathFromUrl($fileAndData->file);
            Storage::disk('public')->delete($relativePathFile); // Gunakan relative path
        }

        // Hapus data dari database
        $fileAndData->delete();

        return response()->json(['message' => 'Resource deleted successfully']);
    }

    /**
     * Extract relative path from full URL for stored image.
     */
    private function extractRelativePathFromUrl($url)
    {
        // Contoh URL: http://localhost:8000/storage/uploads/crew_and_cast/foufo_coba-nich_1751349423.png
        $baseUrl = url('storage'); // http://localhost:8000/storage
        return str_replace($baseUrl . '/', '', $url); // Menghasilkan uploads/crew_and_cast/foufo_coba-nich_1751349423.png
    }


}
