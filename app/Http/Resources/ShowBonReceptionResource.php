<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowBonReceptionResource extends JsonResource
{


    public static $wrap = null;
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'date_reception' => $this->date_reception,
            'type_reception' => $this->type_reception,
            'statut' => $this->statut,
            'responsable_reception' => [
                'id' => $this->responsableReception->id,
                'name' => $this->responsableReception->name,
            ],
            'created_by' => [
                'id' => $this->createdBy->id,
                'name' => $this->createdBy->name,
            ],
            'bon_commande' => [
                'id' => $this->bonCommande->id,
                'reference' => $this->bonCommande->reference
            ],
            'fournisseur' => [
                'id' => $this->fournisseur->id,
                'raison_sociale' => $this->fournisseur->raison_sociale,
                'nom' => $this->fournisseur->nom,
                'contact' => $this->fournisseur->contact,
                'telephone' => $this->fournisseur->telephone,
            ],
            'fichier_bonlivraison' => $this->fichier_bonlivraison,
            'fichier_facture' => $this->fichier_facture,
            'lignes_reception' => $this->lignesReception->map(function ($ligne) {
                return [
                    'id' => $ligne->id,
                    'prix_unitaire' => $ligne->prix_unitaire,
                    'taux_tva' => $ligne->taux_tva,
                    'montant_ht' => $ligne->prix_total,
                    'montant_tva' => $ligne->montant_tva,
                    'montant_ttc' => $ligne->montant_tva + $ligne->prix_total,
                    'article' => [
                        'id' => $ligne->article->id,
                        'reference' => $ligne->article->reference,
                        'designation' => $ligne->article->designation,
                        'unite_mesure' => $ligne->article->unite_mesure,
                    ],
                    'quantite_receptionnee' => $ligne->quantite_receptionnee
                ];
            }),
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
