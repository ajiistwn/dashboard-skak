<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BudgetAndExpense;

class BudgetAndExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = BudgetAndExpense::with(['project', 'categoryExpense'])->get();
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'category_expense_id' => 'required|exists:category_expenses,id',
            'budget' => 'required|numeric|min:0',
            'expense' => 'required|numeric|min:0',
        ]);

        $record = BudgetAndExpense::create($validated);
        return response()->json($record);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $record = BudgetAndExpense::with(['project', 'categoryExpense'])->findOrFail($id);
        return response()->json($record);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $record = BudgetAndExpense::find($id);

        if (!$record) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validated = $request->validate([
            'project_id' => 'sometimes|exists:projects,id',
            'category_expense_id' => 'sometimes|exists:category_expenses,id',
            'budget' => 'sometimes|numeric|min:0',
            'expense' => 'sometimes|numeric|min:0',
        ]);

        $record->update($validated);

        return response()->json($record);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        $record = BudgetAndExpense::find($id);

        if (!$record) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $record->delete();

        return response()->json($record);
    }
}
