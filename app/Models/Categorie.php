<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 
        'code', 
        'description', 
        'categorie_principale_id', 
        'nature_prestation_id', 
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

    // Relation avec Nature Prestation (CORRECTION : le nom correct)
    public function naturePrestation(): BelongsTo
    {
        return $this->belongsTo(NaturePrestation::class, 'nature_prestation_id');
    }

    // Relation avec les Articles
    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function scopeActives($query)
    {
        return $query->where('est_actif', true);
    }

    public function scopeByPrincipale($query, $categoriePrincipaleId)
    {
        return $query->where('categorie_principale_id', $categoriePrincipaleId);
    }
}