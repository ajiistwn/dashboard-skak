<?php

namespace Database\Seeders;

use App\Models\StatusProject;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusesProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        StatusProject::create(['name' => 'On Hold']);
        StatusProject::create(['name' => 'On Planing']);
        StatusProject::create(['name' => 'Development']);
        StatusProject::create(['name' => 'Production']);
        StatusProject::create(['name' => 'Post Production']);
        StatusProject::create(['name' => 'Released']);
        StatusProject::create(['name' => 'Promo']);
        StatusProject::create(['name' => 'Finish']);
    }
}
