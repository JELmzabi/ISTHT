<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoriePrincipale extends Model
{
    use HasFactory;
    protected $table = 'categorie_principales'; // Spécifier le nom de la table


    protected $fillable = [
        'nom', 
        'code', 
        'description', 
        'est_actif'
    ];

    protected $casts = [
        'est_actif' => 'boolean'
    ];

    // Relation avec les Catégories
    public function categories(): HasMany
    {
        return $this->hasMany(Categorie::class);
    }

    // Relation avec les Articles
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    // Relation avec les Natures de Prestation
    public function naturePrestations(): HasMany
    {
        return $this->hasMany(NaturePrestation::class);
    }

    public function scopeActives($query)
    {
        return $query->where('est_actif', true);
    }
}