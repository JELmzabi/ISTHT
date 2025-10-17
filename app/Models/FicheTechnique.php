<?php

namespace App\Models;

use App\Enums\Enums\FicheType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FicheTechnique extends Model
{
    use HasFactory;

    protected $table = 'fiches_techniques';
    
    protected $fillable = [
        'type',
        'nom',
        'plat',
        'responsable',
        'effectif',
        'created_by',
    ];

    protected $casts = [
        'type' => FicheType::class,
    ];


    public function etapes()
    {
        return $this->hasMany(Etape::class, 'fiche_id');
    }

    public function created_by()
    {
        return $this->belongsTo(User::class);
    }
}
