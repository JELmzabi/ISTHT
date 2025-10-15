<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Gardez cette ligne

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
     protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => 'boolean'
    ];

    // Méthodes pour vérifier les rôles
    public function isAdmin()
    {
        return $this->role === 'ADMIN';
    }

    public function isMagasinier()
    {
        return $this->role === 'MAGASINIER';
    }

    public function isDemandeur()
    {
        return $this->role === 'DEMANDEUR';
    }

    public function canGestionStock()
    {
        return $this->isAdmin() || $this->isMagasinier();
    }

    // app/Models/User.php
 public function bonReceptionsResponsable(): HasMany
    {
        return $this->hasMany(BonReception::class, 'responsable_reception_id');
    }

    /**
     * Relation avec les bons de réception créés par l'utilisateur
     */
    public function createdBonReceptions(): HasMany
    {
        return $this->hasMany(BonReception::class, 'created_by');
    }

   
    /**
     * Vérifier si l'utilisateur est un magasinier
     */
    

    /**
     * Vérifier si l'utilisateur est responsable magasin
     */
    
    /**
     * Obtenir le rôle magasinier de l'utilisateur
     */
   // Dans app/Models/User.php
public function scopeMagasiniers($query)
{
    return $query->where('role', 'MAGASINIER');
}
}