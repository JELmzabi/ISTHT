<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArticleImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the article_images table with initial data.
     */
    public function run()
    {
        DB::table('article_images')->insert([
            [
                'id' => 1,
                'article_id' => 447,
                'image_path' => 'articles/images/FzMuB3uc9SaBosTgw4ikwjWPzwfKUdInaSZ9thK9.jpg',
                'nom_fichier' => 'WhatsApp Image 2025-09-15 at 19..jpg',
                'est_principale' => 0,
                'created_at' => '2025-09-29 18:38:25',
                'updated_at' => '2025-09-29 18:38:25'
            ]
        ]);
    }
}