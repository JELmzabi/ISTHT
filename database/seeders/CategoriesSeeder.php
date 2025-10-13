<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the categories table with initial data.
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'nom' => 'légume',
                'code' => 'legume',
                'description' => '',
                'categorie_principale_id' => 1,
                'nature_prestation_id' => 1,
                'est_actif' => 1,
                'created_at' => '2025-09-29 10:08:00',
                'updated_at' => '2025-09-29 10:08:00'
            ],
            [
                'id' => 2,
                'nom' => 'épiserie',
                'code' => 'episerie',
                'description' => '',
                'categorie_principale_id' => 1,
                'nature_prestation_id' => 1,
                'est_actif' => 1,
                'created_at' => '2025-09-29 10:08:00',
                'updated_at' => '2025-09-29 10:08:00'
            ],
            [
                'id' => 3,
                'nom' => 'viande et babats',
                'code' => 'viande-et-babats',
                'description' => '',
                'categorie_principale_id' => 1,
                'nature_prestation_id' => 1,
                'est_actif' => 1,
                'created_at' => '2025-09-29 10:08:00',
                'updated_at' => '2025-09-29 10:08:00'
            ],
            [
                'id' => 4,
                'nom' => 'volaille et oeufs',
                'code' => 'volaille-et-oeufs',
                'description' => '',
                'categorie_principale_id' => 1,
                'nature_prestation_id' => 1,
                'est_actif' => 1,
                'created_at' => '2025-09-29 10:08:00',
                'updated_at' => '2025-09-29 10:08:00'
            ],
            [
                'id' => 5,
                'nom' => 'poisson',
                'code' => 'poisson',
                'description' => '',
                'categorie_principale_id' => 1,
                'nature_prestation_id' => 1,
                'est_actif' => 1,
                'created_at' => '2025-09-29 10:08:00',
                'updated_at' => '2025-09-29 10:08:00'
            ],
            [
                'id' => 6,
                'nom' => 'fruit',
                'code' => 'fruit',
                'description' => null,
                'categorie_principale_id' => 1,
                'nature_prestation_id' => 1,
                'est_actif' => 1,
                'created_at' => null,
                'updated_at' => null
            ]
        ]);
    }
}