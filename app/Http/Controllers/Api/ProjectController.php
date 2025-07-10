<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project; // Assuming this is the model for projects

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil semua data proyek
        $projects = Project::all();

        // Kembalikan data dalam format JSON
        return response()->json([
            'message' => 'Data proyek berhasil diambil.',
            'data' => $projects,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'format_project_id' => 'nullable|exists:format_projects,id',
            'synopsis' => 'nullable|string',
            'image' => 'nullable|string',
            'status_project_id' => 'nullable|exists:status_projects,id',
            'target_date' => 'nullable|string',
            'development' => 'nullable|integer|min:0|max:100',
            'pre_production' => 'nullable|integer|min:0|max:100',
            'production' => 'nullable|integer|min:0|max:100',
            'post_production' => 'nullable|integer|min:0|max:100',
            'promo' => 'nullable|integer|min:0|max:100',
            'target_prod' => 'nullable|date',
            'target_dev' => 'nullable|date',
            'target_rele' => 'nullable|date',
            'web' => 'nullable|string',
        ]);

        // Simpan data proyek baru
        $project = Project::create($validated);


        return response()->json($project);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Temukan proyek berdasarkan ID
        $project = Project::with(['formatProject', 'statusProject'])->findOrFail($id);

        // Kembalikan data dalam format JSON
        return response()->json($project);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validasi input
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'format_project_id' => 'nullable|exists:format_projects,id',
            'synopsis' => 'nullable|string',
            'image' => 'nullable|string',
            'status_project_id' => 'nullable|exists:status_projects,id',
            'target_date' => 'nullable|string',
            'development' => 'nullable|integer|min:0|max:100',
            'pre_production' => 'nullable|integer|min:0|max:100',
            'production' => 'nullable|integer|min:0|max:100',
            'post_production' => 'nullable|integer|min:0|max:100',
            'promo' => 'nullable|integer|min:0|max:100',
            'target_prod' => 'nullable|date',
            'target_dev' => 'nullable|date',
            'target_rele' => 'nullable|date',
            'web' => 'nullable|string',
        ]);

        // Temukan proyek berdasarkan ID
        $project = Project::findOrFail($id);

        // Perbarui data proyek
        $project->update($validated);

        // Kembalikan respons JSON
        return response()->json($project);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Temukan proyek berdasarkan ID
        $project = Project::findOrFail($id);

        // Hapus proyek
        $project->delete();

        // Kembalikan respons JSON
        return response()->json([
            'message' => 'Proyek berhasil dihapus.',
        ]);
    }
}
