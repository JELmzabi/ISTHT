<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonCommandeArticlesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the bon_commande_articles table with initial data.
     */
    public function run()
    {
        DB::table('bon_commande_articles')->insert([
            [
                'id' => 18,
                'bon_commande_id' => 9,
                'article_id' => 1,
                'quantite_commandee' => 120,
                'taux_tva' => 0,
                'prix_unitaire_ht' => 10,
                'montant_ht' => 1200,
                'montant_tva' => 0,
                'montant_ttc' => 1200,
                'created_at' => '2025-10-07 12:36:50',
                'updated_at' => '2025-10-07 12:38:28'
            ],
            [
                'id' => 19,
                'bon_commande_id' => 9,
                'article_id' => 19,
                'quantite_commandee' => 170,
                'taux_tva' => 7,
                'prix_unitaire_ht' => 2,
                'montant_ht' => 340,
                'montant_tva' => 23.8,
                'montant_ttc' => 363.8,
                'created_at' => '2025-10-07 12:36:50',
                'updated_at' => '2025-10-07 12:38:28'
            ],
            [
                'id' => 20,
                'bon_commande_id' => 9,
                'article_id' => 443,
                'quantite_commandee' => 190,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 22.98,
                'montant_ht' => 4366.2,
                'montant_tva' => 873.24,
                'montant_ttc' => 5239.44,
                'created_at' => '2025-10-07 12:36:50',
                'updated_at' => '2025-10-07 12:38:28'
            ],
            [
                'id' => 21,
                'bon_commande_id' => 10,
                'article_id' => 17,
                'quantite_commandee' => 12,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 12,
                'montant_ht' => 144,
                'montant_tva' => 28.8,
                'montant_ttc' => 172.8,
                'created_at' => '2025-10-07 12:41:39',
                'updated_at' => '2025-10-07 12:44:16'
            ],
            [
                'id' => 22,
                'bon_commande_id' => 10,
                'article_id' => 17,
                'quantite_commandee' => 190,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 12,
                'montant_ht' => 2280,
                'montant_tva' => 456,
                'montant_ttc' => 2736,
                'created_at' => '2025-10-07 12:41:39',
                'updated_at' => '2025-10-07 12:44:16'
            ],
            [
                'id' => 23,
                'bon_commande_id' => 11,
                'article_id' => 15,
                'quantite_commandee' => 1908,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 119.99,
                'montant_ht' => 228940.92,
                'montant_tva' => 45788.18,
                'montant_ttc' => 274729.1,
                'created_at' => '2025-10-07 12:42:23',
                'updated_at' => '2025-10-07 12:43:58'
            ],
            [
                'id' => 24,
                'bon_commande_id' => 11,
                'article_id' => 19,
                'quantite_commandee' => 190,
                'taux_tva' => 10,
                'prix_unitaire_ht' => 1219.99,
                'montant_ht' => 231798.1,
                'montant_tva' => 23179.81,
                'montant_ttc' => 254977.91,
                'created_at' => '2025-10-07 12:42:23',
                'updated_at' => '2025-10-07 12:43:58'
            ],
            [
                'id' => 25,
                'bon_commande_id' => 12,
                'article_id' => 14,
                'quantite_commandee' => 190,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 10,
                'montant_ht' => 1900,
                'montant_tva' => 380,
                'montant_ttc' => 2280,
                'created_at' => '2025-10-07 12:43:06',
                'updated_at' => '2025-10-07 12:43:38'
            ],
            [
                'id' => 26,
                'bon_commande_id' => 12,
                'article_id' => 17,
                'quantite_commandee' => 189,
                'taux_tva' => 7,
                'prix_unitaire_ht' => 9.99,
                'montant_ht' => 1888.11,
                'montant_tva' => 132.17,
                'montant_ttc' => 2020.28,
                'created_at' => '2025-10-07 12:43:06',
                'updated_at' => '2025-10-07 12:43:38'
            ],
            [
                'id' => 27,
                'bon_commande_id' => 13,
                'article_id' => 1,
                'quantite_commandee' => 50,
                'taux_tva' => 0,
                'prix_unitaire_ht' => 10,
                'montant_ht' => 500,
                'montant_tva' => 0,
                'montant_ttc' => 500,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 28,
                'bon_commande_id' => 13,
                'article_id' => 19,
                'quantite_commandee' => 30,
                'taux_tva' => 7,
                'prix_unitaire_ht' => 2,
                'montant_ht' => 60,
                'montant_tva' => 4.2,
                'montant_ttc' => 64.2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 29,
                'bon_commande_id' => 14,
                'article_id' => 17,
                'quantite_commandee' => 120,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 11.5,
                'montant_ht' => 1380,
                'montant_tva' => 276,
                'montant_ttc' => 1656,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 30,
                'bon_commande_id' => 14,
                'article_id' => 14,
                'quantite_commandee' => 80,
                'taux_tva' => 7,
                'prix_unitaire_ht' => 9.99,
                'montant_ht' => 799.2,
                'montant_tva' => 55.94,
                'montant_ttc' => 855.14,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 31,
                'bon_commande_id' => 15,
                'article_id' => 443,
                'quantite_commandee' => 200,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 22.98,
                'montant_ht' => 4596,
                'montant_tva' => 919.2,
                'montant_ttc' => 5515.2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 32,
                'bon_commande_id' => 16,
                'article_id' => 17,
                'quantite_commandee' => 150,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 12,
                'montant_ht' => 1800,
                'montant_tva' => 360,
                'montant_ttc' => 2160,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 33,
                'bon_commande_id' => 16,
                'article_id' => 19,
                'quantite_commandee' => 40,
                'taux_tva' => 7,
                'prix_unitaire_ht' => 1.8,
                'montant_ht' => 72,
                'montant_tva' => 5.04,
                'montant_ttc' => 77.04,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 34,
                'bon_commande_id' => 16,
                'article_id' => 1,
                'quantite_commandee' => 25,
                'taux_tva' => 0,
                'prix_unitaire_ht' => 15,
                'montant_ht' => 375,
                'montant_tva' => 0,
                'montant_ttc' => 375,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 35,
                'bon_commande_id' => 17,
                'article_id' => 15,
                'quantite_commandee' => 90,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 119.99,
                'montant_ht' => 10799.1,
                'montant_tva' => 2159.82,
                'montant_ttc' => 12958.92,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 36,
                'bon_commande_id' => 17,
                'article_id' => 19,
                'quantite_commandee' => 60,
                'taux_tva' => 10,
                'prix_unitaire_ht' => 1219.99,
                'montant_ht' => 73199.4,
                'montant_tva' => 7319.94,
                'montant_ttc' => 80519.34,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 37,
                'bon_commande_id' => 18,
                'article_id' => 14,
                'quantite_commandee' => 75,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 10,
                'montant_ht' => 750,
                'montant_tva' => 150,
                'montant_ttc' => 900,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 38,
                'bon_commande_id' => 18,
                'article_id' => 17,
                'quantite_commandee' => 90,
                'taux_tva' => 7,
                'prix_unitaire_ht' => 9.99,
                'montant_ht' => 899.1,
                'montant_tva' => 62.94,
                'montant_ttc' => 962.04,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 39,
                'bon_commande_id' => 19,
                'article_id' => 443,
                'quantite_commandee' => 60,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 22.98,
                'montant_ht' => 1378.8,
                'montant_tva' => 275.76,
                'montant_ttc' => 1654.56,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 40,
                'bon_commande_id' => 19,
                'article_id' => 19,
                'quantite_commandee' => 100,
                'taux_tva' => 7,
                'prix_unitaire_ht' => 2,
                'montant_ht' => 200,
                'montant_tva' => 14,
                'montant_ttc' => 214,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 41,
                'bon_commande_id' => 20,
                'article_id' => 1,
                'quantite_commandee' => 40,
                'taux_tva' => 0,
                'prix_unitaire_ht' => 10,
                'montant_ht' => 400,
                'montant_tva' => 0,
                'montant_ttc' => 400,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 42,
                'bon_commande_id' => 20,
                'article_id' => 17,
                'quantite_commandee' => 55,
                'taux_tva' => 20,
                'prix_unitaire_ht' => 12,
                'montant_ht' => 660,
                'montant_tva' => 132,
                'montant_ttc' => 792,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09'
            ],
            [
                'id' => 43,
                'bon_commande_id' => 21,
                'article_id' => 17,
                'quantite_commandee' => 100,
                'taux_tva' => 10,
                'prix_unitaire_ht' => 100,
                'montant_ht' => 10000,
                'montant_tva' => 1000,
                'montant_ttc' => 11000,
                'created_at' => '2025-10-11 19:55:43',
                'updated_at' => '2025-10-11 19:57:12'
            ],
            [
                'id' => 44,
                'bon_commande_id' => 22,
                'article_id' => 11,
                'quantite_commandee' => 100,
                'taux_tva' => 7,
                'prix_unitaire_ht' => 10,
                'montant_ht' => 1000,
                'montant_tva' => 70,
                'montant_ttc' => 1070,
                'created_at' => '2025-10-12 18:25:37',
                'updated_at' => '2025-10-12 18:30:00'
            ],
            [
                'id' => 45,
                'bon_commande_id' => 22,
                'article_id' => 15,
                'quantite_commandee' => 120,
                'taux_tva' => 0,
                'prix_unitaire_ht' => 12,
                'montant_ht' => 1440,
                'montant_tva' => 0,
                'montant_ttc' => 1440,
                'created_at' => '2025-10-12 18:25:37',
                'updated_at' => '2025-10-12 18:30:00'
            ]
        ]);
    }
}