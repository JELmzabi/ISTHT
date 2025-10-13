<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LigneEntreeStocksSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the ligne_entree_stocks table with initial data.
     */
    public function run()
    {
        DB::table('ligne_entree_stocks')->insert([
            [
                'id' => 4,
                'entree_stock_id' => 7,
                'article_id' => 15,
                'quantite' => 90,
                'prix_unitaire' => 119.99,
                'taux_tva' => 20,
                'montant_tva' => 2159.82,
                'prix_total' => 12958.92,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ],
            [
                'id' => 3,
                'entree_stock_id' => 7,
                'article_id' => 19,
                'quantite' => 60,
                'prix_unitaire' => 1219.99,
                'taux_tva' => 10,
                'montant_tva' => 7319.94,
                'prix_total' => 80519.34,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ],
            [
                'id' => 5,
                'entree_stock_id' => 6,
                'article_id' => 1,
                'quantite' => 25,
                'prix_unitaire' => 15,
                'taux_tva' => 0,
                'montant_tva' => 0,
                'prix_total' => 375,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ],
            [
                'id' => 6,
                'entree_stock_id' => 6,
                'article_id' => 19,
                'quantite' => 40,
                'prix_unitaire' => 1.8,
                'taux_tva' => 7,
                'montant_tva' => 5.04,
                'prix_total' => 77.04,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ],
            [
                'id' => 7,
                'entree_stock_id' => 6,
                'article_id' => 17,
                'quantite' => 150,
                'prix_unitaire' => 12,
                'taux_tva' => 20,
                'montant_tva' => 360,
                'prix_total' => 2160,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ],
            [
                'id' => 8,
                'entree_stock_id' => 5,
                'article_id' => 443,
                'quantite' => 200,
                'prix_unitaire' => 22.98,
                'taux_tva' => 20,
                'montant_tva' => 919.2,
                'prix_total' => 5515.2,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ],
            [
                'id' => 9,
                'entree_stock_id' => 4,
                'article_id' => 14,
                'quantite' => 80,
                'prix_unitaire' => 9.99,
                'taux_tva' => 7,
                'montant_tva' => 55.94,
                'prix_total' => 855.14,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ],
            [
                'id' => 10,
                'entree_stock_id' => 4,
                'article_id' => 17,
                'quantite' => 120,
                'prix_unitaire' => 11.5,
                'taux_tva' => 20,
                'montant_tva' => 276,
                'prix_total' => 1656,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ],
            [
                'id' => 11,
                'entree_stock_id' => 3,
                'article_id' => 19,
                'quantite' => 30,
                'prix_unitaire' => 2,
                'taux_tva' => 7,
                'montant_tva' => 4.2,
                'prix_total' => 64.2,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ],
            [
                'id' => 12,
                'entree_stock_id' => 3,
                'article_id' => 1,
                'quantite' => 50,
                'prix_unitaire' => 10,
                'taux_tva' => 0,
                'montant_tva' => 0,
                'prix_total' => 500,
                'created_at' => '2025-10-11 17:30:10',
                'updated_at' => '2025-10-11 17:30:10'
            ]
        ]);
    }
}