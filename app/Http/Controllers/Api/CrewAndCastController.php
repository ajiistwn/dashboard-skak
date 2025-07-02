<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log; // For logging
use Illuminate\Support\Facades\Storage; // For file storage
use App\Models\CrewAndCast; // Assuming you have a CrewAndCast model

class CrewAndCastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Validasi input filter
        $request->validate([
            'filter' => 'nullable|array',
            'filter.crew' => 'nullable|string',
            'filter.cast' => 'nullable|string',
            'project_id' => 'required|exists:projects,id',
            'search' => 'nullable|string',
        ]);

        // Query dasar: berdasarkan project_id
        $query = CrewAndCast::where('project_id', $request->input('project_id'))
            ->select('id', 'nick_name', 'full_name', 'image', 'job_title', 'group', 'category');


        // Tambahkan filter jika ada
        if ($request->has('filter')) {
            $filters = $request->input('filter');
            // Konversi nilai string ke boolean
            $crew = isset($filters['crew']) ? filter_var($filters['crew'], FILTER_VALIDATE_BOOLEAN) : false;
            $cast = isset($filters['cast']) ? filter_var($filters['cast'], FILTER_VALIDATE_BOOLEAN) : false;

            if ($crew && !$cast) {
                // Jika hanya crew aktif
                $query->where('category', 'crew');
            } elseif (!$crew && $cast) {
                // Jika hanya cast aktif
                $query->where('category', 'cast');
            }
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nick_name', 'like', "%$search%")
                ->orWhere('full_name', 'like', "%$search%")
                ->orWhere('job_title', 'like', "%$search%");
            });
        }

        // Jalankan query dan group by 'group'
        $crewAndCasts = $query->get()->groupBy('group');

        return response()->json($crewAndCasts);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Validasi ID
        $crewAndCast = CrewAndCast::findOrFail($id);
        return response()->json($crewAndCast);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'nick_name' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:2048',
            'date_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'home_town' => 'nullable|string',
            'group' => 'nullable|string',
            'category' => 'required|in:crew,cast',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'character_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');

            // Buat nama file dengan format nick_name_timestamp_hash.extension
            $nickNameSlug = Str::slug($validated['nick_name'], '-'); // Slugify nick_name
            $timestamp = time(); // Waktu saat ini
            $hash = substr(md5($file->getClientOriginalName()), 0, 6); // Hash pendek dari nama file asli
            $extension = $file->getClientOriginalExtension(); // Ekstensi file

            // Gabungkan semua komponen untuk membuat nama file
            $fileName = "{$nickNameSlug}_{$timestamp}_{$hash}.{$extension}";

            // Path folder dan file
            $folderPath = 'uploads/crew_and_cast'; // Nama folder di storage
            $filePath = $folderPath . '/' . $fileName;

            // Pastikan folder ada, jika tidak, buat otomatis
            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            // Simpan file ke storage
            Storage::disk('public')->put($filePath, file_get_contents($file));

            // URL absolut
            $imageUrl = url('storage/' . $filePath);

            // Masukkan ke validated data
            $validated['image'] = $imageUrl;
        }

        // Simpan ke database
        $crewAndCast = CrewAndCast::create($validated);

        return response()->json($crewAndCast);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'nick_name' => 'nullable|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'image' => 'nullable',
            'date_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'home_town' => 'nullable|string',
            'group' => 'nullable|string',
            'category' => 'nullable|in:crew,cast',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'character_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        // Temukan entitas CrewAndCast berdasarkan ID
        $crewAndCast = CrewAndCast::findOrFail($id);

        // Cek apakah ada file gambar baru
        if ($request->hasFile('image')) {
            // Jika ada file gambar baru, simpan gambar baru dan hapus gambar lama
            if ($crewAndCast->image) {
                $relativePath = $this->extractRelativePathFromUrl($crewAndCast->image);
                Storage::disk('public')->delete($relativePath);
            }

            // Simpan file gambar baru ke storage
            $file = $request->file('image');
            $nickNameSlug = Str::slug($request->input('nick_name', $crewAndCast->nick_name), '-'); // Slugify nick_name
            $timestamp = time(); // Waktu saat ini
            $hash = substr(md5($file->getClientOriginalName()), 0, 6); // Hash pendek dari nama file asli
            $extension = $file->getClientOriginalExtension(); // Ekstensi file
            $fileName = "{$nickNameSlug}_{$timestamp}_{$hash}.{$extension}";

            // Path folder dan file
            $folderPath = 'uploads/crew_and_cast'; // Nama folder di storage
            $filePath = $folderPath . '/' . $fileName;

            // Pastikan folder ada, jika tidak, buat otomatis
            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            // Simpan file ke storage
            Storage::disk('public')->put($filePath, file_get_contents($file));

            // URL absolut
            $imageUrl = url('storage/' . $filePath);

            // Masukkan URL gambar baru ke validated data
            $validated['image'] = $imageUrl;
        } elseif ($request->input('image') && $request->input('image') === $crewAndCast->image) {
            // Jika image adalah URL string dan sama dengan yang ada di database, tidak perlu diubah
            unset($validated['image']); // Hapus dari validated data agar tidak di-update
        } elseif ($request->input('image') === null) {
            // Jika request image adalah null, hapus gambar lama dan file-nya
            if ($crewAndCast->image) {
                $relativePath = $this->extractRelativePathFromUrl($crewAndCast->image);
                Storage::disk('public')->delete($relativePath);
            }
            $validated['image'] = null; // Set image menjadi null
        }

        // Update data di database
        $crewAndCast->update($validated);

        // Kembalikan respons JSON
        return response()->json($request->input('image'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temukan entitas CrewAndCast berdasarkan ID
        $crewAndCast = CrewAndCast::findOrFail($id);

        // Periksa apakah ada path file gambar yang tersimpan
        if ($crewAndCast->image) {
            // Ekstrak path relatif dari URL
            $relativePath = $this->extractRelativePathFromUrl($crewAndCast->image);

            // Hapus file gambar dari storage
            Storage::disk('public')->delete($relativePath);
        }


        // Hapus entitas dari database
        $crewAndCast->delete();

        // Kembalikan respons JSON
        return response()->json(['message' => 'Crew/Cast deleted successfully']);
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
