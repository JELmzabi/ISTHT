<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriePrincipalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the categorie_principales table with initial data.
     */
    public function run()
    {
        DB::table('categorie_principales')->insert([
            [
                'id' => 1,
                'nom' => 'Fournitures',
                'code' => 'F01',
                'description' => 'Fournitures',
                'est_actif' => 1,
                'created_at' => '2025-09-28 12:59:57',
                'updated_at' => '2025-09-28 12:59:57'
            ]
        ]);
    }
}