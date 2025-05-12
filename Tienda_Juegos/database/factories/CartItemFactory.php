<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

class CartItemFactory extends Factory
{
    public function definition()
    {
        return [
            'cart_id' => Cart::factory(),
            'game_id' => Game::factory(),
            'quantity' => $this->faker->numberBetween(1, 3)
        ];
    }
}