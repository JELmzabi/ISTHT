<?php
// app/Models/LigneReception.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LigneReception extends Model
{
    protected $fillable = [
        'bon_reception_id',
        'article_id',
        'quantite_receptionnee',
        'prix_unitaire',
        'taux_tva',
        'montant_tva',
        'prix_total'
    ];

    protected $casts = [
        'quantite_receptionnee' => 'decimal:2',
        'prix_unitaire' => 'decimal:2',
        'taux_tva' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'prix_total' => 'decimal:2',
    ];

    public function bonReception(): BelongsTo
    {
        return $this->belongsTo(BonReception::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Calcul automatique des montants avant sauvegarde
        static::saving(function ($ligne) {
            // Calculer les montants
            if ($ligne->quantite_receptionnee && $ligne->prix_unitaire && $ligne->taux_tva) {
                $ligne->prix_total = $ligne->quantite_receptionnee * $ligne->prix_unitaire;
                $ligne->montant_tva = $ligne->prix_total * ($ligne->taux_tva / 100);
            }
        });

        // Mettre à jour le stock après création
        static::created(function ($ligne) {
            try {
                if ($ligne->article && $ligne->quantite_receptionnee > 0) {
                    // Mettre à jour le stock de l'article
                    $ligne->article->increment('quantite_stock', $ligne->quantite_receptionnee);
                    
                    // Créer un mouvement de stock
                    MouvementStock::create([
                        'article_id' => $ligne->article_id,
                        'type_mouvement' => 'entree',
                        'quantite' => $ligne->quantite_receptionnee,
                        'prix_unitaire' => $ligne->prix_unitaire,
                        'reference' => 'BR-' . ($ligne->bonReception->numero ?? 'N/A'),
                        'date_mouvement' => now(),
                        'motif' => 'Réception fournisseur',
                        'created_by' => auth()->id() ?? 1
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de la mise à jour du stock: ' . $e->getMessage());
            }
        });

        // Mettre à jour le stock après suppression
        static::deleted(function ($ligne) {
            try {
                if ($ligne->article && $ligne->quantite_receptionnee > 0) {
                    // Décrementer le stock
                    $ligne->article->decrement('quantite_stock', $ligne->quantite_receptionnee);
                    
                    // Supprimer le mouvement de stock associé
                    MouvementStock::where('article_id', $ligne->article_id)
                        ->where('reference', 'BR-' . ($ligne->bonReception->numero ?? 'N/A'))
                        ->where('type_mouvement', 'entree')
                        ->where('quantite', $ligne->quantite_receptionnee)
                        ->delete();
                }
            } catch (\Exception $e) {
                Log::error('Erreur lors de la suppression du mouvement de stock: ' . $e->getMessage());
            }
        });
    }

    // Accessor pour le montant HT
    public function getMontantHtAttribute(): float
    {
        return $this->prix_total - $this->montant_tva;
    }

    // Vérifier si la ligne est complètement reçue (par rapport à la commande)
    public function estComplete(): bool
    {
        if (!$this->bonReception || !$this->bonReception->bonCommande) {
            return false;
        }

        $ligneCommande = $this->bonReception->bonCommande->articles
            ->firstWhere('article_id', $this->article_id);
            
        if (!$ligneCommande) {
            return false;
        }

        $quantiteTotaleRecue = LigneReception::where('article_id', $this->article_id)
            ->whereHas('bonReception', function ($query) use ($ligneCommande) {
                $query->where('bon_commande_id', $ligneCommande->bon_commande_id);
            })
            ->sum('quantite_receptionnee');

        return $quantiteTotaleRecue >= $ligneCommande->quantite_commandee;
    }

    // Obtenir le reste à recevoir pour cet article
    public function getResteRecevoirAttribute(): float
    {
        if (!$this->bonReception || !$this->bonReception->bonCommande) {
            return 0;
        }

        $ligneCommande = $this->bonReception->bonCommande->articles
            ->firstWhere('article_id', $this->article_id);
            
        if (!$ligneCommande) {
            return 0;
        }

        $quantiteTotaleRecue = LigneReception::where('article_id', $this->article_id)
            ->whereHas('bonReception', function ($query) use ($ligneCommande) {
                $query->where('bon_commande_id', $ligneCommande->bon_commande_id);
            })
            ->sum('quantite_receptionnee');

        return max(0, $ligneCommande->quantite_commandee - $quantiteTotaleRecue);
    }
}