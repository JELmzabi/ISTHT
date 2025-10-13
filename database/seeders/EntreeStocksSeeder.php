<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntreeStocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the entree_stocks table with initial data.
     */
    public function run()
    {
        DB::table('entree_stocks')->insert([
            [
                'id' => 4,
                'numero' => 'ENT-BR-014',
                'bon_reception_id' => 12,
                'fournisseur_id' => 2,
                'date_entree' => '2025-10-11',
                'notes' => 'Entrée auto depuis BR-014',
                'statut' => 'attente_validation',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 3,
                'numero' => 'ENT-BR-013',
                'bon_reception_id' => 11,
                'fournisseur_id' => 1,
                'date_entree' => '2025-10-11',
                'notes' => 'Entrée auto depuis BR-013',
                'statut' => 'attente_validation',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 5,
                'numero' => 'ENT-BR-015',
                'bon_reception_id' => 13,
                'fournisseur_id' => 1,
                'date_entree' => '2025-10-11',
                'notes' => 'Entrée auto depuis BR-015',
                'statut' => 'attente_validation',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 6,
                'numero' => 'ENT-BR-016',
                'bon_reception_id' => 14,
                'fournisseur_id' => 2,
                'date_entree' => '2025-10-11',
                'notes' => 'Entrée auto depuis BR-016',
                'statut' => 'attente_validation',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 7,
                'numero' => 'ENT-BR-017',
                'bon_reception_id' => 15,
                'fournisseur_id' => 1,
                'date_entree' => '2025-10-11',
                'notes' => 'Entrée auto depuis BR-017',
                'statut' => 'attente_validation',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 8,
                'numero' => 1,
                'bon_reception_id' => 17,
                'fournisseur_id' => 1,
                'date_entree' => '2025-10-11',
                'notes' => '\n\nCréé automatiquement à partir du bon de réception BR-000001',
                'statut' => 'attente_validation',
                'created_by' => 2,
                'created_at' => '2025-10-11 18:12:01',
                'updated_at' => '2025-10-11 18:12:01',
                'deleted_at' => null
            ]
        ]);
    }
}