<?php

namespace Database\Factories;

use App\Models\Morceau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Morceau>
 */
class MorceauFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => fake()->sentence(4),
            'duree' => fake()->numberBetween(120, 360), // Durée entre 2 et 6 minutes
            'prix' => fake()->randomFloat(2, 0.99, 2.99), // Prix entre 0.99 et 2.99
            'est_gratuit' => fake()->boolean(20), // 20% de chances d'être une musique gratuite
        ];
    }
}
