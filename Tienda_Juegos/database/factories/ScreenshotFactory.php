<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScreenshotFactory extends Factory
{
    public function definition()
    {
        return [
            'game_id' => Game::factory(),
            'image_path' => $this->faker->imageUrl(1280, 720),
            'order_position' => $this->faker->numberBetween(1, 10)
        ];
    }
}