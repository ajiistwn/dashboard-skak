<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'filters' => 'nullable|array',
            'search' => 'nullable|string',
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

        $agendas = Agenda::with('categoryDocument')
            ->select('id', 'description', 'images', 'date', 'category_documents_id', 'project_id');

        if (!empty($validated['project_id'])) {
            $agendas->where('project_id', $validated['project_id']);
        }

        if (!empty($activeCategoryIds)) {
            $agendas->whereIn('category_documents_id', $activeCategoryIds);
        }

        if (!empty($validated['search'])) {
            $agendas->where(function ($q) use ($validated) {
                $q->where('description', 'like', '%' . $validated['search'] . '%')
                ->orWhereHas('categoryDocument', function ($q) use ($validated) {
                    $q->where('name', 'like', '%' . $validated['search'] . '%')
                        ->orWhere('description', 'like', '%' . $validated['search'] . '%');
                });
            });
        }

        $agendas = $agendas->get();

        // Decode kolom images dari JSON ke array
        $agendas = $agendas->map(function ($agenda) {
            if ($agenda->images) {
                $agenda->images = json_decode($agenda->images, true); // Ubah ke array
            }
            return $agenda;
        });

        // Step 1: Group & Sort by Year DESC
        $groupedYears = $agendas->groupBy(function ($item) {
            return \Carbon\Carbon::parse($item->date)->format('Y');
        })->sortKeysDesc();

        $result = [];

        foreach ($groupedYears as $year => $items) {
            // Group & Sort bulan → pakai sortKeysDesc agar bulan terbesar di atas
            $groupedMonths = $items->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->format('m - F');
            })->sortKeysDesc(); // ini dia solusinya

            $monthsArray = [];
            foreach ($groupedMonths as $month => $monthItems) {
                // Sort agenda dalam bulan → tanggal terbaru di atas
                $sortedAgendas = $monthItems->sortByDesc(function ($agenda) {
                    return $agenda->date;
                })->values();

                $monthsArray[] = [
                    'month' => $month,
                    'agendas' => $sortedAgendas
                ];
            }

            $result[] = [
                'year' => $year,
                'months' => $monthsArray
            ];
        }


        return response()->json($result);
    }



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $agenda = Agenda::find($id);

        if (!$agenda) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // Decode kolom images jika masih dalam format string JSON
        if (is_string($agenda->images)) {
            $agenda->images = json_decode($agenda->images, true);
        }

        return response()->json($agenda);
    }

    /**
     * Store the specified resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'project_id' => 'nullable|exists:projects,id',
            'location' => 'nullable|string|max:255',
            'agenda_type' => 'required|in:schedule,meeting',
            'category_documents_id' => 'nullable|exists:category_documents,id',
            'duration' => 'nullable|string|max:50',
            'meet_type' => 'nullable|in:offline,online',
            'notes' => 'nullable|string',
            'project_link' => 'nullable|url',
            'file_support' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'images' => 'nullable|array',
            'images.*' => 'file|mimes:jpeg,png,jpg,gif',
        ]);

        // Simpan data ke database
        $agenda = new Agenda();
        $agenda->fill($validated);

        // Handle file_support upload
        if ($request->hasFile('file_support')) {
            $fileSupportPath = $this->handleFileUpload($request->file('file_support'), 'file', $validated['project_id'], $validated['description']);
            $agenda->file_support = $fileSupportPath;
        }

        // Handle images upload
        if ($request->hasFile('images')) {
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $this->handleFileUpload($image, 'image', $validated['project_id'], $validated['description']);
                $imagePaths[] = $imagePath;
            }
            $agenda->images = json_encode($imagePaths); // Simpan sebagai JSON
        }

        $agenda->save();

        return response()->json($agenda);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $agenda = Agenda::find($id);
        if (!$agenda) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        if($request->input('imagesPreview')){
            $imagesPreview = json_decode($request->input('imagesPreview'));
        }
        // // Validasi input
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'date' => 'required|date',
            'project_id' => 'nullable|exists:projects,id',
            'location' => 'nullable|string|max:255',
            'agenda_type' => 'required|in:schedule,meeting',
            'category_documents_id' => 'nullable|exists:category_documents,id',
            'duration' => 'nullable|string|max:50',
            'meet_type' => 'nullable|in:offline,online',
            'notes' => 'nullable|string',
            'project_link' => 'nullable|url',
            'file_support' => 'nullable',
            'images' => 'nullable|array',
        ]);

        // Bandingkan gambar dari database dengan imagesPreview
        if ($agenda->images) {
            $existingImages = json_decode($agenda->images, true);

            // Filter existingImages: Hanya simpan URL yang ada di imagesPreview
            $filteredImages = array_filter($existingImages, function ($image) use ($imagesPreview) {
                if (!in_array($image, $imagesPreview)) {
                    // Jika gambar tidak ada di imagesPreview, hapus dari storage
                    $relativePath = $this->extractRelativePathFromUrl($image);
                    Storage::disk('public')->delete($relativePath);
                    return false; // Hapus dari array
                }
                return true; // Tetap simpan di array
            });

            // Update existingImages dengan hasil filter
            $existingImages = array_values($filteredImages); // Reset index array
        }

        // //Handle file_support logic
        if ($request->hasFile('file_support')) {
            // Ada file baru di-upload
            $newFileName = $request->file('file_support')->getClientOriginalName(); // Ambil nama file baru
            // Ekstrak nama file dari URL di database
            $oldFileName = basename($agenda->file_support); // Ambil hanya nama file dari URL
            if ($newFileName !== $oldFileName) {
                // Jika nama file baru berbeda dari nama file lama
                if ($agenda->file_support) {
                    // Hapus file lama dari storage
                    $relativePath = $this->extractRelativePathFromUrl($agenda->file_support);
                    Storage::disk('public')->delete($relativePath);
                }
                // Simpan file baru
                $fileSupportPath = $this->handleFileUpload($request->file('file_support'), 'file', $validated['project_id'], $validated['description']);
                $validated['file_support'] = $fileSupportPath;
            }
            // Jika nama file baru sama dengan nama file lama, tidak perlu melakukan apa pun
        } elseif ($request->input('file_support') === null || $request->input('file_support') === "") {
            // Jika file_support kosong (null)
            if ($agenda->file_support) {
                // Hapus file lama dari storage
                $relativePath = $this->extractRelativePathFromUrl($agenda->file_support);
                Storage::disk('public')->delete($relativePath);
            }
            // Set file_support menjadi null
            $validated['file_support'] = null;
        }


        // // Handle images upload
        $newImages = [];
        if ($request->hasFile('images')) {
            // Upload gambar-gambar baru
            $imagePaths = [];
            foreach ($request->file('images') as $image) {
                $imagePath = $this->handleFileUpload($image, 'image', $validated['project_id'], $validated['description']);
                $imagePaths[] = $imagePath;
            }
            $newImages = $imagePaths;
        }

        $updatedImages = array_merge($imagesPreview ?? [], $newImages ?: []);
        $validated['images'] = $updatedImages;

        // Update data di database
        $agenda->update($validated);

        return response()->json($agenda->images);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $agenda = Agenda::find($id);

        if (!$agenda) {
            return response()->json(['message' => 'Resource not found'], 404);
        }

        // Hapus file_support dari storage
        if ($agenda->file_support) {
            $relativePath = $this->extractRelativePathFromUrl($agenda->file_support);
            Storage::disk('public')->delete($relativePath);
        }

        // Hapus images dari storage
        if ($agenda->images) {
            $existingImages = json_decode($agenda->images, true);
            foreach ($existingImages as $image) {
                $relativePath = $this->extractRelativePathFromUrl($image);
                Storage::disk('public')->delete($relativePath);
            }
        }

        // Hapus data dari database
        $agenda->delete();

        return response()->json(['message' => 'Resource deleted successfully']);
    }
        /**
     * Handle file upload with standardized naming and storage.
     */
    private function handleFileUpload($file, $fieldName, $projectId, $description)
    {
        $projectName = $projectId ? \App\Models\Project::find($projectId)->name : 'no_project';
        $projectSlug = Str::slug($projectName, '-');
        $fileNameSlug = Str::slug($description, '-');
        $timestamp = time();
        $hash = substr(md5($file->getClientOriginalName()), 0, 6);
        $extension = $file->getClientOriginalExtension();

        $fileName = "{$projectSlug}_{$fieldName}_{$fileNameSlug}_{$timestamp}_{$hash}.{$extension}";
        $folderPath = 'uploads/agenda';

        if (!Storage::disk('public')->exists($folderPath)) {
            Storage::disk('public')->makeDirectory($folderPath);
        }

        $filePath = $folderPath . '/' . $fileName;
        Storage::disk('public')->put($filePath, file_get_contents($file));

        return url('storage/' . $filePath);
    }

    /**
     * Extract relative path from full URL for stored files.
     */
    private function extractRelativePathFromUrl($url)
    {
        $baseUrl = url('storage');
        return str_replace($baseUrl . '/', '', $url);
    }
}
