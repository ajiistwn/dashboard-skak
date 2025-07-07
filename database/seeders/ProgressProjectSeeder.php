<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgressProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Data proses pembuatan film
        $progressProjects = [
            ['name' => 'Development'],
            ['name' => 'Pre Production'],
            ['name' => 'Production'],
            ['name' => 'Post Production'],
            ['name' => 'Promo'],
        ];

        // Masukkan data ke dalam tabel progress_projects
        DB::table('progress_projects')->insert($progressProjects);
    }
}
