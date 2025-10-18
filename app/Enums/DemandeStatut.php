<?php

namespace App\Enums;

enum DemandeStatut: string
{

    # cree ─┬─> en_attente_livraison ─┬─> livree
    #       │                                      
    #       └─> annulee                            
    
    case CREE = 'cree';
    case ANNULEE = 'annulee';
    // case APPROUVEE = 'approuvee';
    case EN_ATTENTE = 'en_attente';
    case LIVREE = 'livree';

    public function label(): string
    {
        return match ($this) {
            self::CREE => 'Crée',
            self::EN_ATTENTE => 'En attente de livraison',
            // self::APPROUVEE => 'Approuvée',
            self::LIVREE => 'livrée',
            self::ANNULEE => 'Annulée',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}