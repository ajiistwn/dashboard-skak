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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('description'); // Deskripsi agenda
            $table->dateTime('date'); // Tanggal dan waktu agenda
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete(); // Nama proyek
            $table->string('location')->nullable(); // Lokasi agenda
            $table->enum('agenda_type', ['schedule', 'meeting']); // Jenis agenda
            $table->foreignId('category_documents_id')->nullable()->constrained('category_documents')->nullOnDelete(); // Kategori agenda
            $table->string('duration')->nullable(); // Durasi agenda
            $table->enum('meet_type', ['offline', 'online'])->nullable(); // Tipe pertemuan
            $table->longText('notes')->nullable(); // Catatan tambahan
            $table->string('project_link')->nullable(); // Link proyek
            $table->string('file_support')->nullable();
            $table->json('images')->nullable(); // Gambar pendukung
            $table->json('access'); // Akses yang diizinkan
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
