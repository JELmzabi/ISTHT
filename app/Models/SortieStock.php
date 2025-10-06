<?php
// app/Models/SortieStock.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SortieStock extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero',
        'type_sortie',
        'client_id',
        'date_sortie',
        'motif',
        'notes',
        'statut',
        'created_by'
    ];

    protected $casts = [
        'date_sortie' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Types de sortie
    const TYPE_VENTE = 'vente';
    const TYPE_TRANSFERT = 'transfert';
    const TYPE_PERTE = 'perte';
    const TYPE_AJUSTEMENT = 'ajustement';

    // Statuts
    const STATUT_VALIDE = 'valide';
    const STATUT_ANNULE = 'annule';

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function lignesSortie(): HasMany
    {
        return $this->hasMany(LigneSortieStock::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor pour le numéro d'affichage
    public function getNumeroAffichageAttribute(): string
    {
        return 'SORT-' . str_pad($this->numero, 6, '0', STR_PAD_LEFT);
    }

    // Méthode pour calculer le total
    public function getTotalAttribute(): float
    {
        return $this->lignesSortie->sum('prix_total');
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

        static::created(function ($sortieStock) {
            foreach ($sortieStock->lignesSortie as $ligne) {
                MouvementStock::creerDepuisSortie($ligne);
            }
        });

        static::updated(function ($sortieStock) {
            if ($sortieStock->isDirty('statut') && $sortieStock->statut === self::STATUT_ANNULE) {
                $sortieStock->annulerMouvements();
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

    // Vérifier la disponibilité du stock avant création
    public function verifierDisponibiliteStock(): bool
    {
        foreach ($this->lignesSortie as $ligne) {
            if ($ligne->article->quantite_stock < $ligne->quantite) {
                return false;
            }
        }
        return true;
    }
}