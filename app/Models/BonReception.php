<?php
// app/Models/BonReception.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class BonReception extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'numero',
        'bon_commande_id',
        'fournisseur_id',
        'date_reception',
        'type_reception',
        'statut',
        'responsable_reception_id',
        'fichier_bonlivraison',
        'fichier_facture',
        'notes',
        'created_by'
    ];

    protected $casts = [
        'date_reception' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Types de réception
    const TYPE_COMPLET = 'complet';
    const TYPE_PARTIEL = 'partiel';

    // Statuts
    const STATUT_BROUILLON = 'brouillon';
    const STATUT_VALIDE = 'valide';
    const STATUT_ANNULE = 'annule';

    public function bonCommande(): BelongsTo
    {
        return $this->belongsTo(BonCommande::class);
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

  

   
public function lignesReception(): HasMany
{
    return $this->hasMany(LigneReception::class);
}
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessor pour le numéro d'affichage
    public function getNumeroAffichageAttribute(): string
    {
        return 'BR-' . str_pad($this->numero, 6, '0', STR_PAD_LEFT);
    }


    // Méthode pour calculer le total
    public function getTotalAttribute(): float
    {
        return $this->lignesReception->sum('prix_total');
    }

    // Méthode pour calculer le total TVA
    public function getTotalTvaAttribute(): float
    {
        return $this->lignesReception->sum('montant_tva');
    }

    
    // Méthode pour uploader le fichier bon de livraison
    public function uploadFichierBonlivraison($file): string
    {
        // Supprimer l'ancien fichier s'il existe
        if ($this->fichier_bonlivraison && Storage::disk('public')->exists($this->fichier_bonlivraison)) {
            Storage::disk('public')->delete($this->fichier_bonlivraison);
        }

        $path = $file->store('bon-receptions/bon-livraison', 'public');
        $this->update(['fichier_bonlivraison' => $path]);
        
        return $path;
    }

    // Méthode pour uploader le fichier facture
    public function uploadFichierFacture($file): string
    {
        // Supprimer l'ancien fichier s'il existe
        if ($this->fichier_facture && Storage::disk('public')->exists($this->fichier_facture)) {
            Storage::disk('public')->delete($this->fichier_facture);
        }

        $path = $file->store('bon-receptions/factures', 'public');
        $this->update(['fichier_facture' => $path]);
        
        return $path;
    }

    // Méthode pour supprimer le fichier bon de livraison
    public function supprimerFichierBonlivraison(): void
    {
        if ($this->fichier_bonlivraison && Storage::disk('public')->exists($this->fichier_bonlivraison)) {
            Storage::disk('public')->delete($this->fichier_bonlivraison);
        }
        $this->update(['fichier_bonlivraison' => null]);
    }

    // Méthode pour supprimer le fichier facture
    public function supprimerFichierFacture(): void
    {
        if ($this->fichier_facture && Storage::disk('public')->exists($this->fichier_facture)) {
            Storage::disk('public')->delete($this->fichier_facture);
        }
        $this->update(['fichier_facture' => null]);
    }

    // Méthode pour vérifier si la réception est complète
    public function estReceptionComplete(): bool
    {
        return $this->type_reception === self::TYPE_COMPLET;
    }

    // Méthode pour vérifier si la réception est partielle
    public function estReceptionPartielle(): bool
    {
        return $this->type_reception === self::TYPE_PARTIEL;
    }

    // Scope pour les réceptions complètes
    public function scopeComplets($query)
    {
        return $query->where('type_reception', self::TYPE_COMPLET);
    }

    // Scope pour les réceptions partielles
    public function scopePartiels($query)
    {
        return $query->where('type_reception', self::TYPE_PARTIEL);
    }

    // Scope pour les réceptions avec bon de livraison
    public function scopeAvecBonLivraison($query)
    {
        return $query->whereNotNull('fichier_bonlivraison');
    }

    // Scope pour les réceptions avec facture
    public function scopeAvecFacture($query)
    {
        return $query->whereNotNull('fichier_facture');
    }

    // Méthode pour générer le numéro automatique
    public static function genererNumero(): string
    {
        $lastNumber = self::withTrashed()->max('numero');
        return ($lastNumber ? $lastNumber + 1 : 1);
    }
   public function responsableReception(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsable_reception_id');
    }

      // Accessor pour l'URL du fichier bon de livraison
    public function getFichierBonlivraisonUrlAttribute()
    {
        if ($this->fichier_bonlivraison && Storage::disk('public')->exists($this->fichier_bonlivraison)) {
            return Storage::disk('public')->url($this->fichier_bonlivraison);
        }
        return null;
    }

    // Accessor pour l'URL du fichier facture
    public function getFichierFactureUrlAttribute()
    {
        if ($this->fichier_facture && Storage::disk('public')->exists($this->fichier_facture)) {
            return Storage::disk('public')->url($this->fichier_facture);
        }
        return null;
    }

    // Vérifier si la réception a un fichier bon de livraison
    public function hasFichierBonlivraison(): bool
    {
        return !empty($this->fichier_bonlivraison) && Storage::disk('public')->exists($this->fichier_bonlivraison);
    }

    // Vérifier si la réception a un fichier facture
    public function hasFichierFacture(): bool
    {
        return !empty($this->fichier_facture) && Storage::disk('public')->exists($this->fichier_facture);
    }
}