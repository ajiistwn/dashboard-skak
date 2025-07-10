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
        Schema::create('task_progres_projects', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Nama task
            $table->foreignId('project_id')->nullable()->constrained('category_documents')->nullOnDelete();
            $table->foreignId('progress_project_id')->nullable()->constrained('progress_projects')->nullOnDelete();
            $table->enum('status', ['holdOn','start', 'finish'])->default('start'); // Status task
            $table->timestamp('start_time')->nullable(); // Waktu mulai task
            $table->timestamp('finish_time')->nullable(); // Waktu selesai task
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_progress_projects');
    }
};
