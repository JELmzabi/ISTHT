<?php
// app/Models/EntreeStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    // Statuts
    const STATUT_VALIDE = 'valide';
    const STATUT_ANNULE = 'annule';

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
    public function getNumeroAffichageAttribute(): string
    {
        return 'ENT-' . str_pad($this->numero, 6, '0', STR_PAD_LEFT);
    }

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

    // Méthode pour générer le numéro automatique
    public static function genererNumero(): string
    {
        $lastNumber = self::withTrashed()->max('numero');
        return ($lastNumber ? $lastNumber + 1 : 1);
    }

    // Événements pour gérer les mouvements de stock
    protected static function boot()
    {
        parent::boot();

        static::created(function ($entreeStock) {
            foreach ($entreeStock->lignesEntree as $ligne) {
                MouvementStock::creerDepuisEntree($ligne);
            }
        });

        static::updated(function ($entreeStock) {
            if ($entreeStock->isDirty('statut') && $entreeStock->statut === self::STATUT_ANNULE) {
                $entreeStock->annulerMouvements();
            }
        });
    }

    // Annuler les mouvements associés
    public function annulerMouvements(): void
    {
        MouvementStock::where('source_type', self::class)
            ->where('source_id', $this->id)
            ->delete();
    }
}