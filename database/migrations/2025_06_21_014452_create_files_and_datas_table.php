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
        Schema::create('files_and_datas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_source')->nullable(); //url drive
            $table->foreignId('category_documents_id')->nullable()->constrained('category_documents')->nullOnDelete();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('image')->nullable();
            $table->string('file')->nullable(); //file
            $table->json('access');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_and_data');
    }
};
