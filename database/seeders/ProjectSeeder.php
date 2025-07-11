<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $csvData = [
            [
                'name' => 'Sekawan Limo 2',
                'format_project_id' => 1,
                'synopsis' => 'Lorem ipsum dolor sit amet. Eos voluptatibus nobis ab distinctio fuga qui omnis repudiandae ad consequatur Quis sit iste placeat vel minima aperiam est molestiae nesciunt. Ex modi odio qui fugit quos eos voluptas sunt qui nesciunt dolor ut esse consectetur? Sed aperiam obcaecati et galisum temporibus sed aliquid quia et aspernatur quidem!',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/kcZNw91VUojA7URd3ddH.jpeg',
                'status_project_id' => 1,
                'target_date' => 'Prod: April 2025',
                'development' => 50,
                'pre_production' => 10,
                'production' => 0,
                'post_production' => 0,
                'promo' => 0,
                'target_prod' => '2025-01-31 00:00:00',
                'target_dev' => '2025-01-30 00:00:00',
                'target_rele' => '2025-01-24 00:00:00',
                'web' => '',
                'created_at' => now(),
            ],
            [
                'name' => 'Foufo',
                'format_project_id' => 1,
                'synopsis' => 'Seorang pengepul rongsokan asal Madura yang hidup pas-pasan berharap bisa membesarkan usaha rombengnya dengan meminjam modal Bank. Suatu hari, ia menemukan sebuah pesawat UFO misterius yang terdampar di pinggiran kota. Berharap dapat menjual besi dari pesawat tersebut, ia malah mendapati ada alien di dalamnya yang harus diselamatkan.',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/aHWUd9nKfwabtvYaB2au.jpg',
                'status_project_id' => 2,
                'target_date' => 'Production: 31 May 2025',
                'development' => 100,
                'pre_production' => 100,
                'production' => 80,
                'post_production' => 0,
                'promo' => 0,
                'target_prod' => null,
                'target_dev' => null,
                'target_rele' => null,
                'web' => 'https://foufodashboard.glide.page',
                'created_at' => now(),
            ],
            [
                'name' => 'Expedisi',
                'format_project_id' => 1,
                'synopsis' => 'Lorem ipsum dolor sit amet. Eos voluptatibus nobis ab distinctio fuga qui omnis repudiandae ad consequatur Quis sit iste placeat vel minima aperiam est molestiae nesciunt. Ex modi odio qui fugit quos eos voluptas sunt qui nesciunt dolor ut esse consectetur? Sed aperiam obcaecati et galisum temporibus sed aliquid quia et aspernatur quidem!',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/wmjakckx6Z4eKdS9uCOk.jpeg',
                'status_project_id' => 1,
                'target_date' => 'Dev: April 2025, Prod: Sept 2025',
                'development' => 0,
                'pre_production' => 0,
                'production' => 0,
                'post_production' => 0,
                'promo' => 0,
                'target_prod' => null,
                'target_dev' => null,
                'target_rele' => null,
                'web' => '',
                'created_at' => now(),
            ],
            [
                'name' => 'Sukses Jaya',
                'format_project_id' => 1,
                'synopsis' => 'Lorem ipsum dolor sit amet. Eos voluptatibus nobis ab distinctio fuga qui omnis repudiandae ad consequatur Quis sit iste placeat vel minima aperiam est molestiae nesciunt. Ex modi odio qui fugit quos eos voluptas sunt qui nesciunt dolor ut esse consectetur? Sed aperiam obcaecati et galisum temporibus sed aliquid quia et aspernatur quidem!',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/kAROt0kGNCEFfHa0BVAH.jpeg',
                'status_project_id' => 1,
                'target_date' => 'Dev: July 2025',
                'development' => 0,
                'pre_production' => 0,
                'production' => 0,
                'post_production' => 0,
                'promo' => 0,
                'target_prod' => null,
                'target_dev' => null,
                'target_rele' => null,
                'web' => '',
                'created_at' => now(),
            ],
            [
                'name' => 'Yowisben',
                'format_project_id' => 2,
                'synopsis' => 'Lorem ipsum dolor sit amet. Eos voluptatibus nobis ab distinctio fuga qui omnis repudiandae ad consequatur Quis sit iste placeat vel minima aperiam est molestiae nesciunt. Ex modi odio qui fugit quos eos voluptas sunt qui nesciunt dolor ut esse consectetur? Sed aperiam obcaecati et galisum temporibus sed aliquid quia et aspernatur quidem!',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/tQlAXdjJD5RftHabzhgO.jpeg',
                'status_project_id' => 3,
                'target_date' => 'Dev: October 2025',
                'development' => 0,
                'pre_production' => 0,
                'production' => 0,
                'post_production' => 0,
                'promo' => 0,
                'target_prod' => null,
                'target_dev' => null,
                'target_rele' => null,
                'web' => '',
                'created_at' => now(),
            ],
            [
                'name' => 'Ganja',
                'format_project_id' => 1,
                'synopsis' => 'Lorem ipsum dolor sit amet. Eos voluptatibus nobis ab distinctio fuga qui omnis repudiandae ad consequatur Quis sit iste placeat vel minima aperiam est molestiae nesciunt. Ex modi odio qui fugit quos eos voluptas sunt qui nesciunt dolor ut esse consectetur? Sed aperiam obcaecati et galisum temporibus sed aliquid quia et aspernatur quidem!',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/ILut6gq3hZfReFXF5BXD.jpeg',
                'status_project_id' => 4,
                'target_date' => 'Dev: August 2025',
                'development' => 0,
                'pre_production' => 0,
                'production' => 0,
                'post_production' => 0,
                'promo' => 0,
                'target_prod' => null,
                'target_dev' => null,
                'target_rele' => null,
                'web' => '',
                'created_at' => now(),
            ],
            [
                'name' => 'Cocote Tonggo',
                'format_project_id' => 1,
                'synopsis' => 'Lorem ipsum dolor sit amet. Eos voluptatibus nobis ab distinctio fuga qui omnis repudiandae ad consequatur Quis sit iste placeat vel minima aperiam est molestiae nesciunt. Ex modi odio qui fugit quos eos voluptas sunt qui nesciunt dolor ut esse consectetur? Sed aperiam obcaecati et galisum temporibus sed aliquid quia et aspernatur quidem!',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/9Hvjakx53EUaIUQ9LyZp.jpeg',
                'status_project_id' => 1,
                'target_date' => 'Release: 15 Mei 2025',
                'development' => 100,
                'pre_production' => 100,
                'production' => 100,
                'post_production' => 100,
                'promo' => 20,
                'target_prod' => null,
                'target_dev' => null,
                'target_rele' => null,
                'web' => '',
                'created_at' => now(),
            ],
            [
                'name' => 'Rujak Cingur Lek Har',
                'format_project_id' => 2,
                'synopsis' => '',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/ukG9HAXFBSV8eyXbCx6k.jpeg',
                'status_project_id' => 1,
                'target_date' => '',
                'development' => 100,
                'pre_production' => 100,
                'production' => 100,
                'post_production' => 100,
                'promo' => 100,
                'target_prod' => null,
                'target_dev' => null,
                'target_rele' => null,
                'web' => '',
                'created_at' => now(),
            ],
        ];

        DB::table('projects')->insert($csvData);

    }
}
