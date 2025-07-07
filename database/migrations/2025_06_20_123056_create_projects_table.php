<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama proyek
            $table->foreignId('format_project_id')->nullable()->constrained('format_projects')->nullOnDelete(); // Relasi ke tabel formats
            $table->text('synopsis')->nullable(); // Sinopsis proyek
            $table->string('image')->nullable(); // URL gambar header
            $table->foreignId('status_project_id')->nullable()->constrained('status_projects')->nullOnDelete(); // Relasi ke tabel statuses
            $table->string('target_date')->nullable(); // Target date
            $table->integer('development')->nullable(); // Progress development
            $table->integer('pre_production')->nullable(); // Progress pre-production
            $table->integer('production')->nullable(); // Progress production
            $table->integer('post_production')->nullable(); // Progress post-production
            $table->integer('promo')->nullable(); // Progress promo
            $table->date('target_prod')->nullable(); // Target produksi
            $table->date('target_dev')->nullable(); // Target development
            $table->date('target_rele')->nullable(); // Target release
            $table->string('web')->nullable(); // URL web
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
