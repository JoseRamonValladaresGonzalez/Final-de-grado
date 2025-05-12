<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFeatureFactory extends Factory
{
    public function definition()
    {
        return [
            'game_id' => Game::factory(),
            'feature_text' => $this->faker->sentence()
        ];
    }
}