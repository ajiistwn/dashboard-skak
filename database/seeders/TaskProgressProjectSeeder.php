<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskProgressProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua progress_project_id dari tabel progress_projects
        $progressProjects = DB::table('progress_projects')->pluck('id');

        // Data task untuk setiap progress_project_id
        $tasks = [];
        foreach ($progressProjects as $progressProjectId) {
            for ($i = 1; $i <= 3; $i++) {
                $tasks[] = [
                    'name' => "Task {$i} for Progress Project ID {$progressProjectId}",
                    'project_id' => 2, // Semua project_id bernilai 2
                    'progress_project_id' => $progressProjectId,
                    'status' => $i === 3 ? 'finish' : 'start', // Task ke-3 status finish, sisanya start
                    'start_time' => now()->subDays(3 - $i), // Waktu mulai (3 hari lalu, 2 hari lalu, dst)
                    'finish_time' => $i === 3 ? now() : null, // Waktu selesai hanya untuk task ke-3
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // Masukkan data ke dalam tabel task_progres_projects
        DB::table('task_progres_projects')->insert($tasks);
    }
}
