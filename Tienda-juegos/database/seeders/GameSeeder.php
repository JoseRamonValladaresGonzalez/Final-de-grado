<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Game;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Game::create([
    'title' => 'Final Fantasy X/X-2 HD Remaster',
    'slug' => 'final-fantasy-x',
    'description' => 'Descripción detallada...',
    'price' => 39.99,
    'discount' => 25,
    'developer' => 'Square Enix',
    'publisher' => 'Square Enix',
    'release_date' => '2016-05-12',
    'features' => json_encode(['Remaster HD', 'Doble juego', 'Banda sonora remezclada']),
    'requirements' => json_encode([
        'SO' => 'Windows 7/8.1/10',
        'Procesador' => 'Intel Core 2 Duo',
        'Memoria' => '2 GB RAM',
        'Gràficos' => 'NVIDIA Geforce 9600GT'
    ]),
    'main_image' => 'games/images/ffx-main.jpg',
    'header_image' => 'games/headers/ffx-header.jpg'
]);
    }
}
