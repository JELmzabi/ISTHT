<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemandeArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'demande_id',
        'article_id',
        'quantite_demandee',
        'remarque',
    ];

    public function demande()
    {
        return $this->belongsTo(Demande::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
}
