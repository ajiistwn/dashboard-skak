<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileAndDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'name' => 'FFO_Data Crew',
                'file_source' => 'https://drive.google.com/drive/folders/14sX87aNBTEuhvxJGKeV_96Wpk-voGTI5?usp=drive_link',
                'category_documents_id' => 1, // ID untuk kategori "Legal"
                'project_id' => 1, // ID untuk proyek "Foufo"
                'access' => json_encode(["bod", "team", "partner"]),
                'created_at' => '2025-02-04 15:12:58',
                'updated_at' => '2025-02-04 15:12:58',
            ],
            [
                'name' => 'FFO_Charachter UFO',
                'file_source' => 'https://drive.google.com/drive/folders/1yBqVTePLmsWOoWz5YSb0pD9fUbLMp9O2?usp=sharing',
                'category_documents_id' => 1, // ID untuk kategori "Legal"2, // ID untuk kategori "IP Development"
                'project_id' => 1, // ID untuk proyek "Foufo"
                'access' => json_encode(["bod", "team", "intern", "partner", "crew"]),
                'created_at' => '2025-02-04 15:15:49',
                'updated_at' => '2025-02-04 15:15:49',
            ],
            [
                'name' => 'FFO_Skoci Kecil UFO',
                'file_source' => 'https://drive.google.com/drive/folders/1M64xQEM4HbIJZLm_KqvxLmXG12WkMQVC?usp=sharing',
                'category_documents_id' => 1, // ID untuk kategori "Legal"2, // ID untuk kategori "IP Development"
                'project_id' => 1, // ID untuk proyek "Foufo"
                'access' => json_encode(["bod", "team", "intern", "partner", "crew"]),
                'created_at' => '2025-02-04 19:54:39',
                'updated_at' => '2025-02-04 19:54:39',
            ],
            [
                'name' => 'FFO_Timeline',
                'file_source' => 'https://drive.google.com/file/d/15OarPZHS6dEaKHpCRAO83HBQUcSxyoV-/view?usp=drive_link',
                'category_documents_id' => 1, // ID untuk kategori "Legal"3, // ID untuk kategori "Pre Production"
                'project_id' => 1, // ID untuk proyek "Foufo"
                'access' => json_encode(["bod", "team", "intern", "partner", "crew"]),
                'created_at' => null,
                'updated_at' => null,
            ],
            [
                'name' => 'FFO_Global Sinopsis',
                'file_source' => 'https://drive.google.com/drive/folders/1Oo8CYDZzy6QVb5UkcyCjnpK3h-Rc6gk7?usp=drive_link',
                'category_documents_id' => 1, // ID untuk kategori "Legal"2, // ID untuk kategori "IP Development"
                'project_id' => 1, // ID untuk proyek "Foufo"
                'access' => json_encode(["bod", "team", "intern", "partner", "crew"]),
                'created_at' => '2025-02-07 16:55:16',
                'updated_at' => '2025-02-07 16:55:16',
            ],
            [
                'name' => 'FFO_Scene Plot',
                'file_source' => 'https://drive.google.com/drive/folders/18sb_cbkbVnm731KjqopmlLmgwSmAB_8B?usp=drive_link',
                'category_documents_id' => 1, // ID untuk kategori "Legal"2, // ID untuk kategori "IP Development"
                'project_id' => 1, // ID untuk proyek "Foufo"
                'access' => json_encode(["bod", "team", "intern", "partner", "crew"]),
                'created_at' => '2025-02-19 16:31:55',
                'updated_at' => '2025-02-19 16:31:55',
            ],
            [
                'name' => 'Master Budget Internal',
                'file_source' => 'https://docs.google.com/spreadsheets/d/1jbGdU0ZKHbs3p8Iz5d2En781g2mbMuiS/edit?gid=1417611703#gid=1417611703',
                'category_documents_id' => 1, // ID untuk kategori "Legal"4, // ID untuk kategori "Finance"
                'project_id' => 1, // ID untuk proyek "Foufo"
                'access' => json_encode(["bod"]),
                'created_at' => '2025-03-07 15:41:57',
                'updated_at' => '2025-03-07 15:41:57',
            ],
            [
                'name' => 'FFO_Skenario_Draft 3a',
                'file_source' => 'https://drive.google.com/drive/folders/1BQ84bvyTXC2jUk7h2NYsBEuOIjGSX2ba',
                'category_documents_id' => 1, // ID untuk kategori "Legal"2, // ID untuk kategori "IP Development"
                'project_id' => 1, // ID untuk proyek "Foufo"
                'access' => json_encode(["bod", "team", "partner", "crew"]),
                'created_at' => '2025-03-24 16:52:40',
                'updated_at' => '2025-03-24 16:52:40',
            ],
        ];

        DB::table('files_and_datas')->insert($data);
    }
}
