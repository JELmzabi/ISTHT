<?php
// app/Models/MouvementStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class MouvementStock extends Model
{
    protected $fillable = [
        'article_id',
        'type_mouvement',
        'reference',
        'source_type',
        'source_id',
        'quantite',
        'prix_unitaire',
        'prix_total',
        'stock_avant',
        'stock_apres',
        'date_mouvement',
        'motif',
        'created_by'
    ];

    protected $casts = [
        'quantite' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'prix_total' => 'decimal:2',
        'stock_avant' => 'decimal:2',
        'stock_apres' => 'decimal:2',
        'date_mouvement' => 'date',
    ];

    // Types de mouvement
    const TYPE_ENTREE = 'entree';
    const TYPE_SORTIE = 'sortie';

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Méthode pour créer un mouvement depuis une entrée
    public static function creerDepuisEntree(LigneEntreeStock $ligne): MouvementStock
    {
        $entreeStock = $ligne->entreeStock;
        $article = $ligne->article;

        return self::create([
            'article_id' => $ligne->article_id,
            'type_mouvement' => self::TYPE_ENTREE,
            'reference' => $entreeStock->numero_affichage,
            'source_type' => EntreeStock::class,
            'source_id' => $entreeStock->id,
            'quantite' => $ligne->quantite,
            'prix_unitaire' => $ligne->prix_unitaire,
            'prix_total' => $ligne->prix_total,
            'stock_avant' => $article->quantite_stock - $ligne->quantite,
            'stock_apres' => $article->quantite_stock,
            'date_mouvement' => $entreeStock->date_entree,
            'motif' => 'Réception fournisseur - ' . $entreeStock->fournisseur->nom_affichage,
            'created_by' => $entreeStock->created_by,
        ]);
    }

    // Méthode pour créer un mouvement depuis une sortie
    public static function creerDepuisSortie(LigneSortieStock $ligne): MouvementStock
    {
        $sortieStock = $ligne->sortieStock;
        $article = $ligne->article;

        return self::create([
            'article_id' => $ligne->article_id,
            'type_mouvement' => self::TYPE_SORTIE,
            'reference' => $sortieStock->numero_affichage,
            'source_type' => SortieStock::class,
            'source_id' => $sortieStock->id,
            'quantite' => $ligne->quantite,
            'prix_unitaire' => $ligne->prix_unitaire,
            'prix_total' => $ligne->prix_total,
            'stock_avant' => $article->quantite_stock + $ligne->quantite,
            'stock_apres' => $article->quantite_stock,
            'date_mouvement' => $sortieStock->date_sortie,
            'motif' => self::getMotifSortie($sortieStock),
            'created_by' => $sortieStock->created_by,
        ]);
    }

    // Obtenir le motif selon le type de sortie
    private static function getMotifSortie(SortieStock $sortieStock): string
    {
        $motifs = [
            SortieStock::TYPE_VENTE => 'Vente client',
            SortieStock::TYPE_TRANSFERT => 'Transfert de stock',
            SortieStock::TYPE_PERTE => 'Perte/Donmage',
            SortieStock::TYPE_AJUSTEMENT => 'Ajustement de stock'
        ];

        $motifBase = $motifs[$sortieStock->type_sortie] ?? 'Sortie de stock';
        
        if ($sortieStock->client) {
            $motifBase .= ' - ' . $sortieStock->client->nom;
        }

        return $motifBase . ' - ' . $sortieStock->motif;
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

    // Scope pour un article spécifique
    public function scopePourArticle($query, $articleId)
    {
        return $query->where('article_id', $articleId);
    }

    // Scope pour une période
    public function scopePeriode($query, $dateDebut, $dateFin)
    {
        return $query->whereBetween('date_mouvement', [$dateDebut, $dateFin]);
    }

    // Calculer le stock actuel d'un article
    public static function getStockActuel($articleId): float
    {
        $entrees = self::pourArticle($articleId)->entrees()->sum('quantite');
        $sorties = self::pourArticle($articleId)->sorties()->sum('quantite');
        
        return $entrees - $sorties;
    }
}