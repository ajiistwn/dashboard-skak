<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        // ... validasi dan lainnya ...
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

            // Buat nama file (seperti yang sudah kita buat)
            $projectSlug = '';
            if ($request->filled('project_id')) {
                $project = Project::find((int) $request->project_id);
                if ($project) {
                    $projectSlug = Str::slug($project->name, '-') . '_';
                }
            }

            $nickNameSlug = Str::slug($validated['nick_name'], '-');
            $fileName = $projectSlug . $nickNameSlug . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Path folder dan file
            $folderPath = 'uploads/crew_and_cast'; // Hanya nama folder
            $filePath = $folderPath . '/' . $fileName;

            // Pastikan folder ada, jika tidak, buat otomatis
            if (!Storage::disk('public')->exists($folderPath)) {
                Storage::disk('public')->makeDirectory($folderPath);
            }

            // Simpan file
            Storage::disk('public')->put($filePath, file_get_contents($file));

            // URL absolut
            $imageUrl = url('storage/' . $filePath);

            // Masukkan ke validated data
            $validated['image'] = $imageUrl;
        }

        // Simpan ke database
        $crewAndCast = CrewAndCast::create($validated);

        return response()->json($crewAndCast, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'nick_name' => 'sometimes|required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'job_title' => 'sometimes|required|string|max:255',
            'image' => 'nullable|string',
            'date_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'home_town' => 'nullable|string',
            'group' => 'nullable|string',
            'category' => 'sometimes|required|in:crew,cast',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'character_name' => 'nullable|string',
            'description' => 'nullable|string',
        ]);
        $crewAndCast = CrewAndCast::findOrFail($id);

        $crewAndCast->update($validated);
        return response()->json($crewAndCast);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $crewAndCast = CrewAndCast::findOrFail($id);
        $crewAndCast->delete();
        return response()->json(['message' => 'Crew/Cast deleted successfully']);
    }
}
