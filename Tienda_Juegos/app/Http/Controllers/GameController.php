<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function show(Game $game)
    {
        $game->load([
            'developer',
            'publisher',
            'game_features',
            'screenshots',
            'tags',
            'requirements'
        ]);

        return view('games.show', compact('game'));
    }
}