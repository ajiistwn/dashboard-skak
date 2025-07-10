<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TaskProgressProject; // Assuming this is the model for the task progress project
use App\Models\ProgressProject; // Assuming this is the model for progress projects
use App\Models\Project;

class TaskProgressProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id', // ID project yang ingin difilter
            'filters' => 'nullable|array', // Array of filters (e.g., [1 => true, 2 => false])
            'search' => 'nullable|string', // Search query (e.g., "Crew" or "Legal")
        ]);

        $categoryMapping = [
            'development' => 1,
            'preProduction' => 2,
            'production' => 3,
            'postProduction' => 4,
            'promo' => 5,
        ];

        // Ambil ID kategori yang aktif berdasarkan filters
        $activeCategoryIds = [];
        foreach ($validated['filters'] ?? [] as $categoryName) {
            if (isset($categoryMapping[$categoryName])) {
                $activeCategoryIds[] = $categoryMapping[$categoryName];
            }
        }

        // Query utama untuk mengambil data task
        $query = TaskProgressProject::query()
            ->with(['progressProject' => function ($query) {
                $query->select('id', 'name'); // Hanya ambil kolom id dan name dari progress_project
            }])
            ->select('id', 'name', 'status', 'progress_project_id'); // Hanya ambil kolom yang diperlukan

        // Filter berdasarkan project_id jika ada
        if (isset($validated['project_id'])) {
            $query->where('project_id', $validated['project_id']);
        }

        // Filter berdasarkan activeCategoryIds jika ada
        if (!empty($activeCategoryIds)) {
            $query->whereIn('progress_project_id', $activeCategoryIds);
        }

        // Filter berdasarkan search query jika ada
        if (isset($validated['search'])) {
            $query->where(function ($q) use ($validated) {
                $q->where('name', 'like', '%' . $validated['search'] . '%')
                  ->orWhereHas('progressProject', function ($q) use ($validated) {
                      $q->where('name', 'like', '%' . $validated['search'] . '%');
                  });
            });
        }

        // Ambil data task
        $tasks = $query->get()->groupBy('progressProject.name');


        return response()->json($tasks);

    }

    public function getProgressProject()
    {
        $progressProjects = ProgressProject::select('id', 'name')->get();
        return response()->json($progressProjects);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'project_id' => 'nullable|exists:category_documents,id',
            'progress_project_id' => 'nullable|exists:progress_projects,id',
            'status' => 'nullable|in:start,finish, holdOn',
            'start_time' => 'nullable|date',
            'finish_time' => 'nullable|date',
        ]);

        $task = TaskProgressProject::create($validated);
        // Update the project progress percentages after creating a new task
        if (isset($validated['project_id'])) {
            $this->countPercentageAll($validated['project_id']);
        }

        return response()->json($validated);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $task = TaskProgressProject::with('progressProject')->findOrFail($id);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $task = TaskProgressProject::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'project_id' => 'nullable|exists:category_documents,id',
            'progress_project_id' => 'nullable|exists:progress_projects,id',
            'status' => 'nullable|in:start,finish, holdOn',
            'start_time' => 'nullable|date',
            'finish_time' => 'nullable|date',
        ]);

        $task->update($validated);
        // Update the project progress percentages after updating a task
        if (isset($validated['project_id'])) {
            $this->countPercentageAll($validated['project_id']);
        }

        return response()->json($validated);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $task = TaskProgressProject::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Task deleted successfully']);
    }

    private function countPercentageAll(int $projectId)
    {
        $tasks = TaskProgressProject::where('project_id', $projectId)->get();

        $project = Project::findOrFail($projectId);

        $developmentTasks = $tasks->where('progress_project_id', 1);
        $totalDevelopmentTasks = $developmentTasks->count();
        $finishedDevelopmentTasks = $developmentTasks->where('status', 'finish')->count();
        $developmentPercentage = ($totalDevelopmentTasks > 0) ? ($finishedDevelopmentTasks / $totalDevelopmentTasks) * 100 : 0;

        $preProductionTasks = $tasks->where('progress_project_id', 2);
        $totalPreProductionTasks = $preProductionTasks->count();
        $finishedPreProductionTasks = $preProductionTasks->where('status', 'finish')->count();
        $preProductionPercentage = ($totalPreProductionTasks > 0) ? ($finishedPreProductionTasks / $totalPreProductionTasks) * 100 : 0;

        $productionTasks = $tasks->where('progress_project_id', 3);
        $totalProductionTasks = $productionTasks->count();
        $finishedProductionTasks = $productionTasks->where('status', 'finish')->count();
        $productionPercentage = ($totalProductionTasks > 0) ? ($finishedProductionTasks / $totalProductionTasks) * 100 : 0;

        $postProductionTasks = $tasks->where('progress_project_id', 4);
        $totalPostProductionTasks = $postProductionTasks->count();
        $finishedPostProductionTasks = $postProductionTasks->where('status', 'finish')->count();
        $postProductionPercentage = ($totalPostProductionTasks > 0) ? ($finishedPostProductionTasks / $totalPostProductionTasks) * 100 : 0;

        $promoTasks = $tasks->where('progress_project_id', 5);
        $totalPromoTasks = $promoTasks->count();
        $finishedPromoTasks = $promoTasks->where('status', 'finish')->count();
        $promoPercentage = ($totalPromoTasks > 0) ? ($finishedPromoTasks / $totalPromoTasks) * 100 : 0;

        $project->update([
            'development' => $developmentPercentage,
            'pre_production' => $preProductionPercentage,
            'production' => $productionPercentage,
            'post_production' => $postProductionPercentage,
            'promo' => $promoPercentage,
        ]);


        return response()->json("oke");
    }


}
