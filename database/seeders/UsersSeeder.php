<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'name' => 'Ricky',
                'full_name' => 'Ricky Ramadhan Setiyawan',
                'phone' => '081216384261',
                'email' => 'welliamrick@gmail.com',
                'address' => 'Cilandak Tengah I no.3b, Cilandak Barat, Jakarta Selatan',
                'job_title' => 'Co-Founder & CEO',
                'access' => 'bod',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/E96iCWDSbw2WwuplE7ZE.jpeg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bayu',
                'full_name' => 'Bayu Eko Moektito',
                'phone' => null,
                'email' => 'bayuskak@skakstudios.com',
                'address' => null,
                'job_title' => 'Founder',
                'access' => 'bod',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/7chSWBSorLAGAan2VHYn.jpeg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Henny',
                'full_name' => 'Henny Myranda',
                'phone' => null,
                'email' => 'henny@skakstudios.com',
                'address' => null,
                'job_title' => 'Co-Founder & Business Operational',
                'access' => 'bod',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/gFsuh2QrHiAJpCq0ZJwV.jpeg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Rofiq',
                'full_name' => 'Achmad Rofiq',
                'phone' => '081998412013',
                'email' => 'achmadrofiq88@gmail.com',
                'address' => null,
                'job_title' => 'Co-Founder & Head IP Dev',
                'access' => 'bod',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/UZ8gsyuRzqgZBJ1uH3oo.jpeg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mahes',
                'full_name' => 'I Maheswara Cetta Gantari',
                'phone' => '089513334957',
                'email' => 'maheswara3445@gmail.com',
                'address' => null,
                'job_title' => 'Project Manager',
                'access' => 'bod',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/nKWf8M31PatNWaOjcggR.jpeg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Danial',
                'full_name' => 'Danial Ade Kurniawan',
                'phone' => '08123581755',
                'email' => 'digital@skakstudios.com',
                'address' => null,
                'job_title' => 'Digital & Community Manager',
                'access' => 'team',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/YRBz3FBipKGjvRMfbwWb.jpeg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Faishol',
                'full_name' => 'Achmad Faishol',
                'phone' => '085741375093',
                'email' => 'achfaisol@gmail.com',
                'address' => null,
                'job_title' => 'Staff IP Script Writer & Analyst',
                'access' => 'team',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/tA06KqQc8Dai2VI36ml0.jpg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mardiah',
                'full_name' => 'Mardiah Nafsah',
                'phone' => '085782493250',
                'email' => 'finance@skakstudios.com',
                'address' => null,
                'job_title' => 'Staff Finance & Tax',
                'access' => 'team',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/VVpTVfL78cySrBe2QhTM.jfif',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Thariq',
                'full_name' => 'Muhammad Thariq Aziz',
                'phone' => '081398046091',
                'email' => 'thariqaziz39@gmail.com',
                'address' => null,
                'job_title' => 'Intern Graphic Design',
                'access' => 'intern',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/5FBkAavzcdQF04u1rlxl.jpg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mustofha',
                'full_name' => 'Ahmadul Musthofa Al-Hamdany',
                'phone' => null,
                'email' => 'musthofa.alhamdany@gmail.com',
                'address' => null,
                'job_title' => 'Intern Script Writer Assistant',
                'access' => 'intern',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/7nG9VwiHktVEDIQS94gB.jpg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ibrahimy',
                'full_name' => 'Ibrahimy Mustofa',
                'phone' => null,
                'email' => 'ibrahimymustofa14@gmail.com',
                'address' => null,
                'job_title' => 'Intern Script Writer Assistant',
                'access' => 'intern',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/TE322KrjmfHQ0TmdbPal.jpg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Aji',
                'full_name' => 'Aji Setiawan',
                'phone' => '089636131620',
                'email' => 'ajiisetiawan09@gmail.com',
                'address' => 'Depok',
                'job_title' => 'Intern Information Technology',
                'access' => 'intern',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/YUVgpvU2YATO6RuEURYS.png',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Mahess',
                'full_name' => 'I Maheswara Cetta Gantari',
                'phone' => null,
                'email' => 'management@skakstudios.com',
                'address' => null,
                'job_title' => null,
                'access' => 'bod',
                'image' => 'https://storage.googleapis.com/glide-prod.appspot.com/uploads-v2/ATNLaaCcWTFjYWxEaFqg/pub/EldZQHJzPvExICOQ4WID.jpg',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        // Insert data into the users table
        foreach ($users as $user) {
            DB::table('users')->insert($user);
        }
    }
}
