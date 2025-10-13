<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NaturePrestationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the nature_prestations table with initial data.
     */
    public function run()
    {
        DB::table('nature_prestations')->insert([
            [
                'id' => 1,
                'nom' => 'produits alimentaires pour usage humain',
                'code' => 'PAPUH',
                'description' => 'produits alimentaires pour usage humain',
                'categorie_principale_id' => 1,
                'est_actif' => 1,
                'created_at' => '2025-09-28 13:01:14',
                'updated_at' => '2025-09-28 13:01:14'
            ]
        ]);
    }
}