<?php

namespace Database\Factories;

use App\Models\Fournisseur;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fournisseur>
 */
class FournisseurFactory extends Factory
{
     protected $model = Fournisseur::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->company,
            'raison_sociale' => $this->faker->boolean(70) ? $this->faker->company . ' SARL' : null,
            'contact' => $this->faker->name,
            'telephone' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'adresse' => $this->faker->streetAddress,
            'ville' => $this->faker->city,
            'ice' => strtoupper($this->faker->bothify('#########')), // fake ICE number
            'est_actif' => $this->faker->boolean(80),
            'notes' => $this->faker->optional()->sentence(),
            'logo' => null, // You can later attach fake files manually if needed
        ];
    }

    /**
     * Indique un fournisseur inactif.
     */
    public function inactif(): static
    {
        return $this->state(fn () => ['est_actif' => false]);
    }

    /**
     * Indique un fournisseur actif.
     */
    public function actif(): static
    {
        return $this->state(fn () => ['est_actif' => true]);
    }
}
