<?php

namespace Database\Factories;

use App\Models\Developer;
use App\Models\Publisher;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameFactory extends Factory
{
    public function definition()
    {
        $originalPrice = $this->faker->randomFloat(2, 20, 100);
        $currentPrice = $originalPrice * 0.7; // 30% de descuento
        
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(5),
            'developer_id' => Developer::factory(),
            'publisher_id' => Publisher::factory(),
            'release_date' => $this->faker->dateTimeBetween('-5 years'),
            'main_image' => $this->faker->imageUrl(640, 480, 'games'),
            'original_price' => $originalPrice,
            'current_price' => $currentPrice,
            'discount_percent' => 30
        ];
    }
}