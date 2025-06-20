<?php

namespace Database\Seeders;

use App\Models\FormatProject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormatsProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        FormatProject::create(['name' => 'Film']);
        FormatProject::create(['name' => 'Animation']);
        FormatProject::create(['name' => 'Series']);
    }
}
