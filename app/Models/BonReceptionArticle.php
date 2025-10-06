<?php
// app/Models/BonReceptionArticle.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonReceptionArticle extends Model
{
    protected $fillable = [
        'bon_reception_id', 'article_id', 'quantite_recue',
        'prix_unitaire', 'taux_tva', 'montant_tva', 'montant_ht', 'montant_ttc'
    ];

    protected $casts = [
        'quantite_recue' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'montant_ht' => 'decimal:2',
        'montant_ttc' => 'decimal:2',
    ];

    public function bonReception(): BelongsTo
    {
        return $this->belongsTo(BonReception::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}