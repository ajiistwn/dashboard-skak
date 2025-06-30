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
        Schema::create('crew_and_casts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            $table->string('nick_name'); // Nama panggilan
            $table->string('full_name')->nullable(); // Nama lengkap
            $table->string('job_title')->nullable(); // Deskripsi pekerjaan
            $table->string('image')->nullable(); // URL foto profil
            $table->date('date_birth')->nullable(); // Tanggal lahir
            $table->string('address')->nullable(); // Alamat
            $table->string('home_town')->nullable(); // Kota asal
            $table->string('group')->nullable(); // Grup/tim
            $table->enum('category', ['crew', 'cast']); // Kategori: Crew atau Cast
            $table->string('email')->nullable(); // Email
            $table->string('phone')->nullable(); // Nomor telepon
            $table->string('character_name')->nullable(); // Nama karakter (hanya untuk Cast)
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('crew_and_casts');
    }
};
