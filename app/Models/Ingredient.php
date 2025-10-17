<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $table = 'ingredients';
    
    protected $fillable = [
        'prix_unitaire',
        'quantite',
        'taux_tva',
        'article_id',
        'etape_id',
    ];


    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }

    public function etape()
    {
        return $this->belongsTo(Etape::class, 'etape_id');
    }
}
