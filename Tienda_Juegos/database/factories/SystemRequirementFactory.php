<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class SystemRequirementFactory extends Factory
{
    public function definition()
    {
        return [
            'game_id' => Game::factory(),
            'requirement_type' => $this->faker->randomElement(['minimum', 'recommended']),
            'os' => 'Windows '.$this->faker->randomElement([7, 10, 11]).' 64-bit',
            'processor' => $this->faker->randomElement(['Intel Core i5', 'AMD Ryzen 5']).' '.$this->faker->numberBetween(2000, 5000),
            'memory' => $this->faker->randomElement(['4 GB', '8 GB', '16 GB']).' RAM',
            'graphics' => $this->faker->randomElement(['NVIDIA GTX 1060', 'AMD Radeon RX 580']),
            'directx' => 'Version '.$this->faker->numberBetween(9, 12),
            'storage' => $this->faker->numberBetween(20, 100).' GB espacio disponible'
        ];
    }
}