<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Agenda;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

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

        // Validasi input
        $validator = Validator::make($request->all(), [
            'description' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'project_id' => 'nullable|exists:projects,id',
            'location' => 'nullable|string|max:255',
            'agenda_type' => 'sometimes|required|in:schedule,meeting',
            'category_documents_id' => 'nullable|exists:category_documents,id',
            'duration' => 'nullable|string|max:50',
            'meet_type' => 'nullable|in:offline,online',
            'notes' => 'nullable|string',
            'project_link' => 'nullable|url',
            'file_support' => 'nullable|string|max:255',
            'images' => 'nullable|array', // JSON field
            'access' => 'sometimes|required|array', // JSON field
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Update data
        $agenda->update($request->all());

        return response()->json($agenda);
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

        $agenda->delete();

        return response()->json(['message' => 'Resource deleted successfully']);
    }
}
