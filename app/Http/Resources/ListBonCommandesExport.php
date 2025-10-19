<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListBonCommandesExport extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'reference' => $this->reference, 
            'objet' => $this->objct, 
            'categorie_principale' => $this->categoriePrincipale->nom,
            'nature_prestation' => $this->naturePrestation->nom, 
            'fournisseur' => $this->fournisseur->nom, 
            'statut' => $this->getStatusLabel($this->statut), 
            
            'date_mise_ligne' => $this->date_mise_ligne->toDateString(), 
            'date_limite_reception' => $this->date_limite_reception->toDateString(),
        ];
    }


    private function getStatusLabel($status) 
    {
        switch ($status) {
            case 'cree':
                return 'Cree';
            case 'attente_livraison':
                return 'Attente Livraison';
            case 'livre_completement':
                return 'Livre Completement';
            case 'livre_partiellement':
                return 'Livre Partiellement';
            default:
                return 'Inconnu';
        }
    }
}
