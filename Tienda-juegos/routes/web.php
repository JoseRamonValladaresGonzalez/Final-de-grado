<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Models\Game;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [GameController::class, 'index'])->name('home');
Route::get('/juego/{slug}', [GameController::class, 'show'])->name('game.show');