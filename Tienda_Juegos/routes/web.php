<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CartController;
use App\Models\Game;

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::get('/', function () {
    $featuredGames = Game::with(['developer', 'publisher'])
        ->where('discount_percent', '>', 20)
        ->latest()
        ->take(3)
        ->get();

    $games = Game::with(['developer', 'publisher'])
        ->latest()
        ->paginate(6);

    return view('home', compact('featuredGames', 'games'));
})->name('home');

Route::resource('games', GameController::class)->only(['show']);

Route::middleware('auth')->group(function(){
    Route::get('/cart', [CartController::class,'index'])->name('cart.index');
    Route::post('/cart/{game}', [CartController::class,'store'])->name('cart.store');
    Route::patch('/cart/increment/{cartItem}', [CartController::class,'increment'])->name('cart.increment');
    Route::patch('/cart/decrement/{cartItem}', [CartController::class,'decrement'])->name('cart.decrement');
    Route::delete('/cart/remove/{cartItem}', [CartController::class,'remove'])->name('cart.remove');
});

Route::post('/checkout', [CartController::class, 'checkout'])
    ->name('checkout')
    ->middleware('auth');
require __DIR__.'/auth.php';

