<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::with('screenshots')
                    ->orderBy('release_date', 'desc')
                    ->paginate(10);

        return view('home', compact('games'));
    }

    public function show($slug)
    {
        $game = Game::with('screenshots', 'genres')
                    ->where('slug', $slug)
                    ->firstOrFail();

        return view('game-detail', compact('game'));
    }
}