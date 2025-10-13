<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BonReceptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the bon_receptions table with initial data.
     */
    public function run()
    {
        DB::table('bon_receptions')->insert([
            [
                'id' => 15,
                'numero' => 'BR-017',
                'bon_commande_id' => 17,
                'fournisseur_id' => 1,
                'date_reception' => '2025-10-11 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 3,
                'fichier_bonlivraison' => null,
                'fichier_facture' => null,
                'notes' => 'Réception complète de BC2025-017',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 13,
                'numero' => 'BR-015',
                'bon_commande_id' => 15,
                'fournisseur_id' => 1,
                'date_reception' => '2025-10-11 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 5,
                'fichier_bonlivraison' => null,
                'fichier_facture' => null,
                'notes' => 'Réception complète de BC2025-015',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 14,
                'numero' => 'BR-016',
                'bon_commande_id' => 16,
                'fournisseur_id' => 2,
                'date_reception' => '2025-10-11 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 6,
                'fichier_bonlivraison' => null,
                'fichier_facture' => null,
                'notes' => 'Réception complète de BC2025-016',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 12,
                'numero' => 'BR-014',
                'bon_commande_id' => 14,
                'fournisseur_id' => 2,
                'date_reception' => '2025-10-11 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 4,
                'fichier_bonlivraison' => null,
                'fichier_facture' => null,
                'notes' => 'Réception complète de BC2025-014',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 10,
                'numero' => 3,
                'bon_commande_id' => 11,
                'fournisseur_id' => 1,
                'date_reception' => '2025-10-07 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 6,
                'fichier_bonlivraison' => 'bon-receptions/bon-livraison/BL_1759851809_10.pdf',
                'fichier_facture' => null,
                'notes' => null,
                'created_by' => 2,
                'created_at' => '2025-10-07 14:43:29',
                'updated_at' => '2025-10-07 14:43:29',
                'deleted_at' => null
            ],
            [
                'id' => 11,
                'numero' => 'BR-013',
                'bon_commande_id' => 13,
                'fournisseur_id' => 1,
                'date_reception' => '2025-10-11 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 3,
                'fichier_bonlivraison' => null,
                'fichier_facture' => null,
                'notes' => 'Réception complète de BC2025-013',
                'created_by' => 2,
                'created_at' => '2025-10-11 17:30:09',
                'updated_at' => '2025-10-11 17:30:09',
                'deleted_at' => null
            ],
            [
                'id' => 9,
                'numero' => 2,
                'bon_commande_id' => 12,
                'fournisseur_id' => 2,
                'date_reception' => '2025-10-08 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 5,
                'fichier_bonlivraison' => 'bon-receptions/bon-livraison/BL_1759847468_9.pdf',
                'fichier_facture' => 'bon-receptions/factures/FACT_1759847468_9.pdf',
                'notes' => null,
                'created_by' => 2,
                'created_at' => '2025-10-07 13:31:08',
                'updated_at' => '2025-10-07 13:31:08',
                'deleted_at' => null
            ],
            [
                'id' => 8,
                'numero' => 1,
                'bon_commande_id' => 9,
                'fournisseur_id' => 1,
                'date_reception' => '2025-10-07 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 4,
                'fichier_bonlivraison' => 'bon-receptions/bon-livraison/BL_1759844411_8.pdf',
                'fichier_facture' => 'bon-receptions/factures/FACT_1759844411_8.pdf',
                'notes' => null,
                'created_by' => 2,
                'created_at' => '2025-10-07 12:40:11',
                'updated_at' => '2025-10-07 12:40:11',
                'deleted_at' => null
            ],
            [
                'id' => 16,
                'numero' => 1,
                'bon_commande_id' => 18,
                'fournisseur_id' => 2,
                'date_reception' => '2025-10-11 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 6,
                'fichier_bonlivraison' => null,
                'fichier_facture' => null,
                'notes' => null,
                'created_by' => 2,
                'created_at' => '2025-10-11 18:09:08',
                'updated_at' => '2025-10-11 18:09:08',
                'deleted_at' => null
            ],
            [
                'id' => 17,
                'numero' => 1,
                'bon_commande_id' => 19,
                'fournisseur_id' => 1,
                'date_reception' => '2025-10-11 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 4,
                'fichier_bonlivraison' => 'bon-receptions/bon-livraison/kE2I3adXYK3YS24moopaa8RgdibOVPC54w69q0RJ.pdf',
                'fichier_facture' => 'bon-receptions/factures/ZzO7ZcVyWO4YOWVsddKHMc5faNq2X4oJNvyfYJit.pdf',
                'notes' => null,
                'created_by' => 2,
                'created_at' => '2025-10-11 18:12:01',
                'updated_at' => '2025-10-11 18:12:01',
                'deleted_at' => null
            ],
            [
                'id' => 18,
                'numero' => 1,
                'bon_commande_id' => 22,
                'fournisseur_id' => 2,
                'date_reception' => '2025-10-12 00:00:00',
                'type_reception' => 'complet',
                'statut' => 'valide',
                'responsable_reception_id' => 5,
                'fichier_bonlivraison' => null,
                'fichier_facture' => null,
                'notes' => null,
                'created_by' => 2,
                'created_at' => '2025-10-12 18:39:24',
                'updated_at' => '2025-10-12 18:39:24',
                'deleted_at' => null
            ]
        ]);
    }
}