<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\BonCommande;
use App\Models\BonCommandeArticle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BonCommandeArticle>
 */
class BonCommandeArticleFactory extends Factory
{
    protected $model = BonCommandeArticle::class;

    public function definition(): array
    {
        $quantite = $this->faker->numberBetween(10, 50);
        $prixHt = $this->faker->randomFloat(2, 10, 200);
        $tauxTva = collect([0, 5, 7, 10, 15, 20])->random(); // 20% TVA

        $montantHt = $quantite * $prixHt;
        $montantTva = $montantHt * $tauxTva / 100;
        $montantTtc = $montantHt + $montantTva;

        return [
            'article_id' => Article::inRandomOrder()->first()->id,
            'quantite_commandee' => $quantite,
            'prix_unitaire_ht' => $prixHt,
            'taux_tva' => $tauxTva,
            'montant_ht' => $montantHt,
            'montant_tva' => $montantTva,
            'montant_ttc' => $montantTtc,
        ];
    }
}
