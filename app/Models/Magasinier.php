<?php
// app/Models/Magasinier.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Magasinier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'nom_complet',
        'role',
        'telephone',
        'matricule',
        'est_actif',
        'notes'
    ];

    protected $casts = [
        'est_actif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Rôles disponibles
    const ROLE_MAGASINIER = 'magasinier';
    const ROLE_RESPONSABLE = 'responsable_magasin';
    const ROLE_ASSISTANT = 'assistant_magasin';

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les bons de réception (en tant que responsable)
     */
    public function bonReceptionsResponsable()
    {
        return $this->hasMany(BonReception::class, 'responsable_reception_id');
    }

    /**
     * Scope pour les magasiniers actifs
     */
    public function scopeActifs($query)
    {
        return $query->where('est_actif', true);
    }

    /**
     * Scope pour un rôle spécifique
     */
    public function scopeParRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Accessor pour le nom complet formaté
     */
    public function getNomCompletFormateAttribute()
    {
        return "{$this->nom_complet} ({$this->role})";
    }

    /**
     * Vérifier si le magasinier est responsable
     */
    public function estResponsable(): bool
    {
        return $this->role === self::ROLE_RESPONSABLE;
    }

    /**
     * Vérifier si le magasinier est un simple magasinier
     */
    public function estMagasinier(): bool
    {
        return $this->role === self::ROLE_MAGASINIER;
    }

    /**
     * Liste des rôles disponibles
     */
    public static function rolesDisponibles(): array
    {
        return [
            self::ROLE_MAGASINIER => 'Magasinier',
            self::ROLE_RESPONSABLE => 'Responsable Magasin',
            self::ROLE_ASSISTANT => 'Assistant Magasin',
        ];
    }

    /**
     * Libellé du rôle
     */
    public function getRoleLabelAttribute(): string
    {
        return self::rolesDisponibles()[$this->role] ?? $this->role;
    }
}