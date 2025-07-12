<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetAndExpense extends Model
{
    //
    protected $table = 'budget_and_expenses';
    protected $guarded = [];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function categoryExpense()
    {
        return $this->belongsTo(CategoryExpense::class);
    }
}
