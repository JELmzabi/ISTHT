<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneEntreeStock extends Model
{
    protected $fillable = [
        'entree_stock_id',
        'article_id',
        'quantite',
        'prix_unitaire',
        'taux_tva',
        'montant_tva',
        'prix_total'
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'prix_total' => 'decimal:2',
    ];

    public function entreeStock(): BelongsTo
    {
        return $this->belongsTo(EntreeStock::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    // Calcul automatique des montants
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($ligne) {
            if ($ligne->quantite && $ligne->prix_unitaire) {
                $ligne->prix_total = $ligne->quantite * $ligne->prix_unitaire;
                if ($ligne->taux_tva) {
                    $ligne->montant_tva = $ligne->prix_total * ($ligne->taux_tva / 100);
                }
            }
        });
    }

    // Accessor pour le montant HT
    public function getMontantHtAttribute(): float
    {
        return $this->prix_total - $this->montant_tva;
    }
}