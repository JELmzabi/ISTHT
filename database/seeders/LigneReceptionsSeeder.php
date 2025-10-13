<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LigneReceptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the ligne_receptions table with initial data.
     */
    public function run()
    {
        DB::table('ligne_receptions')->insert([
            [
                'id' => 36,
                'bon_reception_id' => 17,
                'article_id' => 19,
                'quantite_receptionnee' => 100,
                'prix_unitaire' => 2,
                'taux_tva' => 7,
                'montant_tva' => 14,
                'prix_total' => 200,
                'created_at' => '2025-10-11 18:12:01',
                'updated_at' => '2025-10-11 18:12:01'
            ],
            [
                'id' => 35,
                'bon_reception_id' => 15,
                'article_id' => 19,
                'quantite_receptionnee' => 60,
                'prix_unitaire' => 1219.99,
                'taux_tva' => 10,
                'montant_tva' => 7319.94,
                'prix_total' => 80519.34,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 34,
                'bon_reception_id' => 15,
                'article_id' => 15,
                'quantite_receptionnee' => 90,
                'prix_unitaire' => 119.99,
                'taux_tva' => 20,
                'montant_tva' => 2159.82,
                'prix_total' => 12958.92,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 33,
                'bon_reception_id' => 14,
                'article_id' => 1,
                'quantite_receptionnee' => 25,
                'prix_unitaire' => 15,
                'taux_tva' => 0,
                'montant_tva' => 0,
                'prix_total' => 375,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 32,
                'bon_reception_id' => 14,
                'article_id' => 19,
                'quantite_receptionnee' => 40,
                'prix_unitaire' => 1.8,
                'taux_tva' => 7,
                'montant_tva' => 5.04,
                'prix_total' => 77.04,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 31,
                'bon_reception_id' => 14,
                'article_id' => 17,
                'quantite_receptionnee' => 150,
                'prix_unitaire' => 12,
                'taux_tva' => 20,
                'montant_tva' => 360,
                'prix_total' => 2160,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 30,
                'bon_reception_id' => 13,
                'article_id' => 443,
                'quantite_receptionnee' => 200,
                'prix_unitaire' => 22.98,
                'taux_tva' => 20,
                'montant_tva' => 919.2,
                'prix_total' => 5515.2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 29,
                'bon_reception_id' => 12,
                'article_id' => 14,
                'quantite_receptionnee' => 80,
                'prix_unitaire' => 9.99,
                'taux_tva' => 7,
                'montant_tva' => 55.94,
                'prix_total' => 855.14,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 28,
                'bon_reception_id' => 12,
                'article_id' => 17,
                'quantite_receptionnee' => 120,
                'prix_unitaire' => 11.5,
                'taux_tva' => 20,
                'montant_tva' => 276,
                'prix_total' => 1656,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 27,
                'bon_reception_id' => 11,
                'article_id' => 19,
                'quantite_receptionnee' => 30,
                'prix_unitaire' => 2,
                'taux_tva' => 7,
                'montant_tva' => 4.2,
                'prix_total' => 64.2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 26,
                'bon_reception_id' => 11,
                'article_id' => 1,
                'quantite_receptionnee' => 50,
                'prix_unitaire' => 10,
                'taux_tva' => 0,
                'montant_tva' => 0,
                'prix_total' => 500,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 25,
                'bon_reception_id' => 10,
                'article_id' => 19,
                'quantite_receptionnee' => 190,
                'prix_unitaire' => 1219.99,
                'taux_tva' => 10,
                'montant_tva' => 23179.81,
                'prix_total' => 254977.91,
                'created_at' => '2025-10-07 14:43:29',
                'updated_at' => '2025-10-07 14:43:29'
            ],
            [
                'id' => 24,
                'bon_reception_id' => 10,
                'article_id' => 15,
                'quantite_receptionnee' => 1908,
                'prix_unitaire' => 119.99,
                'taux_tva' => 20,
                'montant_tva' => 45788.18,
                'prix_total' => 274729.1,
                'created_at' => '2025-10-07 14:43:29',
                'updated_at' => '2025-10-07 14:43:29'
            ],
            [
                'id' => 23,
                'bon_reception_id' => 9,
                'article_id' => 17,
                'quantite_receptionnee' => 189,
                'prix_unitaire' => 9.99,
                'taux_tva' => 7,
                'montant_tva' => 132.17,
                'prix_total' => 2020.28,
                'created_at' => '2025-10-07 13:31:08',
                'updated_at' => '2025-10-07 13:31:08'
            ],
            [
                'id' => 22,
                'bon_reception_id' => 9,
                'article_id' => 14,
                'quantite_receptionnee' => 190,
                'prix_unitaire' => 10,
                'taux_tva' => 20,
                'montant_tva' => 380,
                'prix_total' => 2280,
                'created_at' => '2025-10-07 13:31:08',
                'updated_at' => '2025-10-07 13:31:08'
            ],
            [
                'id' => 21,
                'bon_reception_id' => 8,
                'article_id' => 443,
                'quantite_receptionnee' => 190,
                'prix_unitaire' => 22.98,
                'taux_tva' => 20,
                'montant_tva' => 873.24,
                'prix_total' => 5239.44,
                'created_at' => '2025-10-07 12:40:11',
                'updated_at' => '2025-10-07 12:40:11'
            ],
            [
                'id' => 20,
                'bon_reception_id' => 8,
                'article_id' => 19,
                'quantite_receptionnee' => 170,
                'prix_unitaire' => 2,
                'taux_tva' => 7,
                'montant_tva' => 23.8,
                'prix_total' => 363.8,
                'created_at' => '2025-10-07 12:40:11',
                'updated_at' => '2025-10-07 12:40:11'
            ],
            [
                'id' => 19,
                'bon_reception_id' => 8,
                'article_id' => 1,
                'quantite_receptionnee' => 120,
                'prix_unitaire' => 10,
                'taux_tva' => 0,
                'montant_tva' => 0,
                'prix_total' => 1200,
                'created_at' => '2025-10-07 12:40:11',
                'updated_at' => '2025-10-07 12:40:11'
            ],
            [
                'id' => 37,
                'bon_reception_id' => 17,
                'article_id' => 443,
                'quantite_receptionnee' => 60,
                'prix_unitaire' => 22.98,
                'taux_tva' => 20,
                'montant_tva' => 275.76,
                'prix_total' => 1378.8,
                'created_at' => '2025-10-11 18:12:01',
                'updated_at' => '2025-10-11 18:12:01'
            ],
            [
                'id' => 38,
                'bon_reception_id' => 18,
                'article_id' => 11,
                'quantite_receptionnee' => 100,
                'prix_unitaire' => 10,
                'taux_tva' => 7,
                'montant_tva' => 70,
                'prix_total' => 1000,
                'created_at' => '2025-10-12 18:39:24',
                'updated_at' => '2025-10-12 18:39:24'
            ],
            [
                'id' => 39,
                'bon_reception_id' => 18,
                'article_id' => 15,
                'quantite_receptionnee' => 120,
                'prix_unitaire' => 12,
                'taux_tva' => 0,
                'montant_tva' => 0,
                'prix_total' => 1440,
                'created_at' => '2025-10-12 18:39:24',
                'updated_at' => '2025-10-12 18:39:24'
            ]
        ]);
    }
}