<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryExpense extends Model
{
    //
    protected $table = 'category_expenses';
    protected $guarded = [];

    public function budgetAndExpenses()
    {
        return $this->hasMany(BudgetAndExpense::class);
    }
}
