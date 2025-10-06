<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NaturePrestation extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 
        'code', 
        'description', 
        'categorie_principale_id', 
        'est_actif'
    ];

    protected $casts = [
        'est_actif' => 'boolean'
    ];

    // Relation avec Catégorie Principale
    public function categoriePrincipale(): BelongsTo
    {
        return $this->belongsTo(CategoriePrincipale::class);
    }

    // Relation avec les Catégories
    public function categories(): HasMany
    {
        return $this->hasMany(Categorie::class, 'nature_prestation_id');
    }

    // Relation avec les Articles
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class, 'nature_prestation_id');
    }

    public function scopeActives($query)
    {
        return $query->where('est_actif', true);
    }
}