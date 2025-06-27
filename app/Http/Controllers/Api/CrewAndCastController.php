<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CrewAndCast; // Assuming you have a CrewAndCast model

class CrewAndCastController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $crewAndCasts = CrewAndCast::with('')->get();
        return response()->json('$crewAndCasts');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'nick_name' => 'required|string|max:255',
            'full_name' => 'nullable|string|max:255',
            'job_title' => 'required|string|max:255',
            'image' => 'nullable|string',
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
        $crewAndCast = CrewAndCast::create($validated);
        return response()->json($crewAndCast, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $crewAndCast = CrewAndCast::with('project')->findOrFail($id);
        return response()->json($crewAndCast);
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
