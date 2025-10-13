<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistoriqueStatutBcsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Populates the historique_statut_bcs table with initial data.
     */
    public function run()
    {
        DB::table('historique_statut_bcs')->insert([
            [
                'id' => 1,
                'bon_commande_id' => 13,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-09-30 15:06:54',
                'updated_at' => '2025-09-30 15:06:54'
            ],
            [
                'id' => 2,
                'bon_commande_id' => 14,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-01 15:59:18',
                'updated_at' => '2025-10-01 15:59:18'
            ],
            [
                'id' => 3,
                'bon_commande_id' => 14,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'annule',
                'raison' => 'AZERTYUIOPOKJHGFHGUGV',
                'changed_by' => 2,
                'created_at' => '2025-10-01 16:42:20',
                'updated_at' => '2025-10-01 16:42:20'
            ],
            [
                'id' => 4,
                'bon_commande_id' => 13,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Changement de statut',
                'changed_by' => 2,
                'created_at' => '2025-10-01 17:22:51',
                'updated_at' => '2025-10-01 17:22:51'
            ],
            [
                'id' => 5,
                'bon_commande_id' => 14,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Changement de statut',
                'changed_by' => 2,
                'created_at' => '2025-10-02 11:04:11',
                'updated_at' => '2025-10-02 11:04:11'
            ],
            [
                'id' => 6,
                'bon_commande_id' => 15,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-04 12:03:04',
                'updated_at' => '2025-10-04 12:03:04'
            ],
            [
                'id' => 7,
                'bon_commande_id' => 15,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-04 20:35:08',
                'updated_at' => '2025-10-04 20:35:08'
            ],
            [
                'id' => 8,
                'bon_commande_id' => 15,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-04 21:04:57',
                'updated_at' => '2025-10-04 21:04:57'
            ],
            [
                'id' => 9,
                'bon_commande_id' => 16,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-05 15:35:54',
                'updated_at' => '2025-10-05 15:35:54'
            ],
            [
                'id' => 10,
                'bon_commande_id' => 16,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-05 15:37:00',
                'updated_at' => '2025-10-05 15:37:00'
            ],
            [
                'id' => 11,
                'bon_commande_id' => 17,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-06 15:45:43',
                'updated_at' => '2025-10-06 15:45:43'
            ],
            [
                'id' => 12,
                'bon_commande_id' => 17,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-06 15:47:19',
                'updated_at' => '2025-10-06 15:47:19'
            ],
            [
                'id' => 13,
                'bon_commande_id' => 18,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-06 19:46:57',
                'updated_at' => '2025-10-06 19:46:57'
            ],
            [
                'id' => 14,
                'bon_commande_id' => 18,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-06 19:47:54',
                'updated_at' => '2025-10-06 19:47:54'
            ],
            [
                'id' => 15,
                'bon_commande_id' => 19,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-07 12:36:50',
                'updated_at' => '2025-10-07 12:36:50'
            ],
            [
                'id' => 16,
                'bon_commande_id' => 19,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'LE MOTIF D\'annnulation',
                'changed_by' => 2,
                'created_at' => '2025-10-07 12:38:28',
                'updated_at' => '2025-10-07 12:38:28'
            ],
            [
                'id' => 17,
                'bon_commande_id' => 10,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-07 12:41:39',
                'updated_at' => '2025-10-07 12:41:39'
            ],
            [
                'id' => 18,
                'bon_commande_id' => 11,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-07 12:42:23',
                'updated_at' => '2025-10-07 12:42:23'
            ],
            [
                'id' => 19,
                'bon_commande_id' => 12,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-07 12:43:06',
                'updated_at' => '2025-10-07 12:43:06'
            ],
            [
                'id' => 20,
                'bon_commande_id' => 12,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-07 12:43:38',
                'updated_at' => '2025-10-07 12:43:38'
            ],
            [
                'id' => 21,
                'bon_commande_id' => 11,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-07 12:43:59',
                'updated_at' => '2025-10-07 12:43:59'
            ],
            [
                'id' => 22,
                'bon_commande_id' => 10,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-07 12:44:16',
                'updated_at' => '2025-10-07 12:44:16'
            ],
            [
                'id' => 23,
                'bon_commande_id' => 21,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-11 19:55:43',
                'updated_at' => '2025-10-11 19:55:43'
            ],
            [
                'id' => 24,
                'bon_commande_id' => 21,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-11 19:57:12',
                'updated_at' => '2025-10-11 19:57:12'
            ],
            [
                'id' => 25,
                'bon_commande_id' => 22,
                'ancien_statut' => 'nouveau',
                'nouveau_statut' => 'cree',
                'raison' => 'Création du bon de commande',
                'changed_by' => 2,
                'created_at' => '2025-10-12 18:25:37',
                'updated_at' => '2025-10-12 18:25:37'
            ],
            [
                'id' => 26,
                'bon_commande_id' => 22,
                'ancien_statut' => 'cree',
                'nouveau_statut' => 'attente_livraison',
                'raison' => 'Mise à jour du statut avec attribution fournisseur',
                'changed_by' => 2,
                'created_at' => '2025-10-12 18:30:00',
                'updated_at' => '2025-10-12 18:30:00'
            ]
        ]);
    }
}