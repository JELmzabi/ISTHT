<?php

namespace App\Models;

use App\Enums\DemandeStatut;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Demande extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'numero',
        'demandeur_id',
        'motif',
        'statut',
        'date_demande',
        'date_validation',
        'valide_par',
        'commentaire_validation',
    ];

    protected $dates = [
        'statut' => DemandeStatut::class,
        'date_demande',
        'date_validation',
    ];

    public static function generateNumero(): string
    {
        $lastDemande = Demande::orderBy('created_at', 'desc')->first();
        $lastDemandeNumber = $lastDemande ? $lastDemande->id : 0;
        return "DE-" . ($lastDemandeNumber + 1);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this
            ->addMediaCollection('fiche-technique')
            ->singleFile();
    }

    public function demandeur()
    {
        return $this->belongsTo(User::class, 'demandeur_id');
    }

    public function valideur()
    {
        return $this->belongsTo(User::class, 'valide_par');
    }

    public function articles()
    {
        return $this->hasMany(DemandeArticle::class, 'demande_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors & Helpers
    |--------------------------------------------------------------------------
    */

    public function getStatutLabelAttribute(): string
    {
        return $this->statut->label();
    }

    public function scopeWithStatut($query, DemandeStatut $statut)
    {
        return $query->where('statut', $statut->value);
    }

    public function scopePending($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function scopeApproved($query)
    {
        return $query->where('statut', 'approuvee');
    }
}
