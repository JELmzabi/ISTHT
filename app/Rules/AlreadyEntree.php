<?php

namespace App\Rules;

use App\Models\Article;
use App\Models\MouvementStock;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AlreadyEntree implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $alreadyEntree = MouvementStock::entrees()->where('article_id', $value)->first();

        if (!$alreadyEntree) {
            $article = Article::find($value);
            $articleName = $article ? $article->designation : 'Inconnu';
            $fail("L'article « {$articleName} » n'a jamais été enregistré dans un stock d'entrée.");
        }
    }
}
