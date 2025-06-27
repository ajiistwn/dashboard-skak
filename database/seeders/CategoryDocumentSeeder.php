<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoryDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            ['name' => 'Development'],
            ['name' => 'Pre Production'],
            ['name' => 'Production'],
            ['name' => 'Post Production'],
            ['name' => 'Promo'],
        ];

        foreach ($categories as $category) {
            \App\Models\CategoryDocument::create($category);
        }
    }
}
