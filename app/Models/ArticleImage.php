<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArticleImage extends Model
{
    use HasFactory;

    protected $fillable = ['article_id', 'image_path', 'nom_fichier', 'est_principale'];

    protected $casts = [
        'est_principale' => 'boolean'
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->image_path);
    }
}