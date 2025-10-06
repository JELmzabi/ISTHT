<?php
// app/Models/BonCommandeArticle.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonCommandeArticle extends Model
{
    protected $table = 'bon_commande_articles';

    protected $fillable = [
        'bon_commande_id',
        'article_id',
        'quantite_commandee',
        'taux_tva',
        'prix_unitaire_ht',    // ← AJOUTER
        'montant_ht',          // ← AJOUTER
        'montant_tva',         // ← AJOUTER
        'montant_ttc',         // ← AJOUTER
    ];

    protected $casts = [
        'quantite_commandee' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'prix_unitaire_ht' => 'decimal:2',  // ← AJOUTER
        'montant_ht' => 'decimal:2',        // ← AJOUTER
        'montant_tva' => 'decimal:2',       // ← AJOUTER
        'montant_ttc' => 'decimal:2',       // ← AJOUTER
    ];

    public function bonCommande(): BelongsTo
    {
        return $this->belongsTo(BonCommande::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}