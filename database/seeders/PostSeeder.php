<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $posts = [
            [
                'title'        => 'Membangun REST API dengan Laravel 13',
                'slug'         => 'membangun-rest-api-dengan-laravel-13',
                'excerpt'      => 'Panduan lengkap membangun REST API production-ready dengan Laravel 13, Sanctum, dan Docker.',
                'body'         => 'Laravel 13 membawa banyak perubahan signifikan dibanding versi sebelumnya. Salah satu yang paling terasa adalah struktur aplikasi yang lebih ramping (slim application skeleton). Di artikel ini kita akan membangun REST API dari nol menggunakan Laravel 13 dengan autentikasi Sanctum dan deployment menggunakan Docker...',
                'status'       => 'published',
                'published_at' => now(),
            ],
            [
                'title'        => 'Docker untuk Laravel Developer Pemula',
                'slug'         => 'docker-untuk-laravel-developer-pemula',
                'excerpt'      => 'Pengenalan Docker untuk developer Laravel — dari konsep dasar sampai docker-compose.',
                'body'         => 'Docker adalah salah satu tools yang wajib dikuasai developer modern. Dengan Docker, kamu bisa memastikan aplikasi berjalan sama persis di semua environment — laptop developer, server staging, maupun server production. Tidak ada lagi masalah "di komputer saya jalan, kenapa di server tidak?"...',
                'status'       => 'published',
                'published_at' => now()->subDays(3),
            ],
            [
                'title'        => 'Mengenal React untuk Backend Developer',
                'slug'         => 'mengenal-react-untuk-backend-developer',
                'excerpt'      => 'Panduan React dari sudut pandang backend developer yang sudah familiar dengan Laravel.',
                'body'         => 'Sebagai backend developer yang terbiasa dengan Laravel, belajar React bisa terasa overwhelming di awal. Tapi sebenarnya banyak konsep yang mirip — component di React mirip seperti Blade component, state mirip seperti session, dan props mirip seperti passing data ke view...',
                'status'       => 'draft',
                'published_at' => null,
            ],
        ];

        foreach ($posts as $post) {
            Post::create($post);
        }
    }
}
