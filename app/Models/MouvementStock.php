<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MouvementStock extends Model
{

    protected $appends = ['quantite'];

    
    protected $fillable = [
        'type',
        'article_id',
        'created_by',
        'date_mouvement',
        'prix_unitaire',
        'taux_tva',
        'type_mouvement',
        'quantite_entree',
        'quantite_sortie',
        'quantite_actuelle',
        'referenceable',
        'motif',
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'stock_apres' => 'decimal:2',
        'quantite_entree' => 'decimal:2',
        'quantite_sortie' => 'decimal:2',
        'quantite_actuelle' => 'decimal:2',
        'date_mouvement' => 'datetime',
    ];

    // Types de mouvement
    const TYPE_ENTREE = 'entree';
    const TYPE_SORTIE = 'sortie';
    const M_TYPE_RECEPTION = 'reception';
    const TYPE_INVENTAIRE = 'inventaire';
    const TYPE_CORRECTION = 'correction';

    const M_TYPE_INRENAL = 'internal_use';

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function source()
    {
        return $this->morphTo();
    }

    // Méthode pour créer un mouvement depuis une entrée
    public static function creerDepuisEntree(LigneEntreeStock $ligneEntree): self
    {
        $article = $ligneEntree->article;
        $entreeStock = $ligneEntree->entreeStock;

        return self::create([
            'article_id' => $ligneEntree->article_id,
            'type_mouvement' => self::TYPE_ENTREE,
            'quantite' => $ligneEntree->quantite,
            'prix_unitaire' => $ligneEntree->prix_unitaire,
            'reference' => $entreeStock->numero_affichage,
            'date_mouvement' => $entreeStock->date_entree,
            'motif' => 'Entrée stock - ' . ($entreeStock->bonReception->numero_affichage ?? 'N/A'),
            'stock_avant' => $article->quantite_stock - $ligneEntree->quantite,
            'stock_apres' => $article->quantite_stock,
            'source_type' => get_class($entreeStock),
            'source_id' => $entreeStock->id,
            'created_by' => $entreeStock->created_by,
        ]);
    }

    // Scope pour les entrées
    public function scopeEntrees($query)
    {
        return $query->where('type_mouvement', self::TYPE_ENTREE);
    }

    // Scope pour les sorties
    public function scopeSorties($query)
    {
        return $query->where('type_mouvement', self::TYPE_SORTIE);
    }

    // Scope par article
    public function scopeParArticle($query, $articleId)
    {
        return $query->where('article_id', $articleId);
    }

    // Scope par période
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_mouvement', [$dateDebut, $dateFin]);
    }

    public function getQuantiteAttribute()
    {
        $quantity = $this->type == self::TYPE_ENTREE ? $this->quantite_entree : $this->quantite_sortie;
        return $quantity;
    }

    public function getTotalTtcAttribute()
    {
        return $this->quantite * $this->prix_unitaire * (1 + ($this->taux_tva / 100));
    }

    public function getTotalHtAttribute()
    {
        return $this->quantite * $this->prix_unitaire;
    }

    public function getMontantHtAttribute()
    {
        return $this->quantite * $this->prix_unitaire;
    }

    public function getMontantTvaAttribute()
    {
        return $this->quantite * $this->prix_unitaire * ($this->taux_tva / 100);
    }

}