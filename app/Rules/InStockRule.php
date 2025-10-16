<?php

namespace App\Rules;

use App\Models\Article;
use App\Models\MouvementStock;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class InStockRule implements DataAwareRule, ValidationRule
{
    /**
     * All of the data under validation.
     *
     * @var array<string, mixed>
     */
    protected $data = [];
 
    // ...
 
    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;
 
        return $this;
    }
    
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // $attribute = articles.0.quantite
        preg_match('/articles\.(\d+)\.quantite/', $attribute, $matches);
        if (!$matches) {
            $fail("Erreur de validation sur l'article.");
            return;
        }

        $index = (int) $matches[1];

        // get the article_id from the same index
        if (!isset($this->data['articles'][$index]['article_id'])) {
            $fail("Article introuvable.");
            return;
        }

        $article_id = $this->data['articles'][$index]['article_id'];
        $article = Article::find($article_id);

        if (!$article) {
            $fail("Article introuvable.");
            return;
        }

        $article_name = $article->designation;

        if ($value > $article->quantite_stock) {
            $fail("La quantité demandée pour l'article « {$article_name} » ({$value}) dépasse le stock disponible ({$article->quantite_stock}).");
        }
    }
}
