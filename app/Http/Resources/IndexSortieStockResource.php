<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexSortieStockResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'numero' => $this->numero,
            'type' => $this->type_sortie,
            'date' => $this->date_sortie->toDateString(),
            'articles_count' => $this->lignesSortie()->count(),
            'statut' => $this->statut,
            'demandeur' => $this->demandeur?->name ?? '---',
            'created_by' => $this->createdBy?->name ?? 'Système', 
        ];
    }
}
