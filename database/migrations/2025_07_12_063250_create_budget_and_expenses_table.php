<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('budget_and_expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade'); // Foreign key untuk project
            $table->foreignId('category_expense_id')->constrained()->onDelete('cascade'); // Foreign key untuk category_expense
            $table->decimal('budget', 15, 0)->default(0); // Nominal budget dalam rupiah (misal: 1000000.00)
            $table->decimal('expense', 15, 0)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('budget_and_expenses');
    }
};
