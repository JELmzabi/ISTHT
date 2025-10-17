<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etape extends Model
{
    use HasFactory;

    protected $table = 'etaps';
    
    protected $fillable = [
        'title',
        'fiche_id',
    ];


    public function fiche()
    {
        return $this->belongsTo(FicheTechnique::class, 'fiche_id');
    }

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
