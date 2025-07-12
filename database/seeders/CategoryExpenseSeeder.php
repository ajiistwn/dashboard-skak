<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            ['name' => 'Development'],
            ['name' => 'Crew'],
            ['name' => 'Cast'],
            ['name' => 'Equipment'],
            ['name' => 'Art'],
            ['name' => 'Transportation'],
            ['name' => 'Accomodation'],
            ['name' => 'Meal'],
            ['name' => 'Production'],
            ['name' => 'Post Production'],
            ['name' => 'CGI & VFX'],
            ['name' => 'Scoring & Mixing'],
        ];

        // Masukkan data ke tabel category_expenses
        DB::table('category_expenses')->insert($data);
    }
}
