<?php
// app/Models/LigneSortieStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LigneSortieStock extends Model
{
    protected $fillable = [
        'sortie_stock_id',
        'article_id',
        'quantite',
        'prix_unitaire',
        'taux_tva',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'taux_tva' => 'decimal:2',
    ];

    public function sortieStock(): BelongsTo
    {
        return $this->belongsTo(SortieStock::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

}