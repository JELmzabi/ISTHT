<?php
// app/Models/EntreeStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class EntreeStock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero',
        'bon_reception_id',
        'fournisseur_id',
        'date_entree',
        'notes',
        'statut',
        'created_by'
    ];

    protected $casts = [
        'date_entree' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Dans app/Models/EntreeStock.php

// Ajoutez ces constantes pour les statuts
const STATUT_ATTENTE_VALIDATION = 'attente_validation';
const STATUT_VALIDE = 'valide';
const STATUT_ANNULE = 'annule';

// Ajoutez cette méthode pour valider l'entrée de stock
public function valider()
{
    DB::transaction(function () {
        $this->update(['statut' => self::STATUT_VALIDE]);
        
        // Mettre à jour les stocks des articles
        foreach ($this->lignesEntree as $ligne) {
            if ($ligne->article) {
                $ligne->article->increment('quantite_stock', $ligne->quantite);
                
                // Créer un mouvement de stock
                MouvementStock::create([
                    'article_id' => $ligne->article_id,
                    'type_mouvement' => 'entree',
                    'quantite' => $ligne->quantite,
                    'prix_unitaire' => $ligne->prix_unitaire,
                    'reference' => 'ES-' . $this->numero_affichage,
                    'date_mouvement' => $this->date_entree,
                    'motif' => 'Entrée de stock validée',
                    'created_by' => auth()->id() ?? 1
                ]);
            }
        }
    });
}

// Méthode pour annuler l'entrée de stock
public function annuler()
{
    DB::transaction(function () {
        $this->update(['statut' => self::STATUT_ANNULE]);
        
        // Si l'entrée était validée, déduire les stocks
        if ($this->getOriginal('statut') === self::STATUT_VALIDE) {
            foreach ($this->lignesEntree as $ligne) {
                if ($ligne->article) {
                    $ligne->article->decrement('quantite_stock', $ligne->quantite);
                    
                    // Supprimer les mouvements de stock associés
                    MouvementStock::where('article_id', $ligne->article_id)
                        ->where('reference', 'ES-' . $this->numero_affichage)
                        ->where('type_mouvement', 'entree')
                        ->delete();
                }
            }
        }
    });
}

// Accessor pour vérifier si l'entrée peut être validée
public function getPeutEtreValideAttribute(): bool
{
    return $this->statut === self::STATUT_ATTENTE_VALIDATION;
}

// Accessor pour vérifier si l'entrée peut être annulée
public function getPeutEtreAnnuleAttribute(): bool
{
    return $this->statut === self::STATUT_ATTENTE_VALIDATION;
}

    public function bonReception(): BelongsTo
    {
        return $this->belongsTo(BonReception::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function lignesEntree(): HasMany
    {
        return $this->hasMany(LigneEntreeStock::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor pour le numéro d'affichage
  

    // Méthode pour calculer le total
    public function getTotalAttribute(): float
    {
        return $this->lignesEntree->sum('prix_total');
    }

    // Méthode pour calculer le total TVA
    public function getTotalTvaAttribute(): float
    {
        return $this->lignesEntree->sum('montant_tva');
    }

  
    // Méthode pour créer une entrée de stock depuis une réception
    public static function creerDepuisReception(BonReception $bonReception): self
    {
        $entreeStock = self::create([
            'numero' => self::genererNumero(),
            'bon_reception_id' => $bonReception->id,
            'fournisseur_id' => $bonReception->fournisseur_id,
            'date_entree' => $bonReception->date_reception,
            'statut' => self::STATUT_ATTENTE_VALIDATION,
            'notes' => "Entrée créée automatiquement depuis la réception " . $bonReception->numero_affichage,
            'created_by' => $bonReception->created_by,
        ]);

        // Créer les lignes d'entrée
        foreach ($bonReception->lignesReception as $ligneReception) {
            LigneEntreeStock::create([
                'entree_stock_id' => $entreeStock->id,
                'article_id' => $ligneReception->article_id,
                'quantite' => $ligneReception->quantite_receptionnee,
                'prix_unitaire' => $ligneReception->prix_unitaire,
                'taux_tva' => $ligneReception->taux_tva,
                'montant_tva' => $ligneReception->montant_tva,
                'prix_total' => $ligneReception->prix_total,
            ]);
        }

        return $entreeStock;
    }

    // Vérifier si l'entrée peut être validée
    public function peutEtreValidee(): bool
    {
        return $this->statut === self::STATUT_ATTENTE_VALIDATION;
    }

    // Valider l'entrée de stock

    // Annuler les mouvements associés
    public function annulerMouvements(): void
    {
        MouvementStock::where('source_type', self::class)
            ->where('source_id', $this->id)
            ->delete();
    }

    // Scope pour les entrées en attente de validation
    public function scopeEnAttenteValidation($query)
    {
        return $query->where('statut', self::STATUT_ATTENTE_VALIDATION);
    }

    // Scope pour les entrées validées
    public function scopeValidees($query)
    {
        return $query->where('statut', self::STATUT_VALIDE);
    }

    // Scope pour les entrées annulées
    public function scopeAnnulees($query)
    {
        return $query->where('statut', self::STATUT_ANNULE);
    }

        public static function genererNumero() // Retourne un int au lieu de string
    {
        $lastNumber = (int) self::withTrashed()->max('id') ?? 0;
        $numero = 'ENTR-' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        return $numero;
    }

    // Accessor pour le numéro d'affichage - CORRIGÉ
    public function getNumeroAffichageAttribute(): string
    {
        return $this->numero;
        
    }
}