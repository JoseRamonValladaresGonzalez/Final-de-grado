<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\CartController;
use App\Models\Game;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\adminController;
use App\Http\Controllers\Admin\DeveloperController;
use App\Http\Controllers\Admin\PublisherController;


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

Route::middleware('auth')->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{game}', [CartController::class, 'store'])->name('cart.store');
    Route::patch('/cart/increment/{pivot_id}', [CartController::class, 'increment'])->name('cart.increment');
    Route::patch('/cart/decrement/{pivot_id}', [CartController::class, 'decrement'])->name('cart.decrement');
    Route::delete('/cart/remove/{pivot_id}', [CartController::class, 'destroy'])->name('cart.remove');
});

Route::post('/checkout', [CartController::class, 'checkout'])
    ->name('checkout')
    ->middleware('auth');
require __DIR__ . '/auth.php';

Route::middleware('auth')->group(function () {
    // Ruta para iniciar checkout de Stripe (POST)
    Route::post('/stripe/checkout', [StripeController::class, 'checkout'])
        ->name('stripe.checkout');

    // Ruta de éxito después del pago
    Route::get('/stripe/success', [StripeController::class, 'success'])
        ->name('stripe.success');
});
Route::get('/test', [StripeController::class, 'test'])
    ->name('test')
    ->middleware('auth');

Route::prefix('admin')->name('admin.')->group(function () {
    // Login
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);

    // Rutas protegidas
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('games', [adminController::class, 'index'])->name('games.index');
        Route::get('games/create', [adminController::class, 'create'])->name('games.create');
        Route::post('games', [adminController::class, 'store'])->name('games.store');
        Route::get('games/{game}/edit', [adminController::class, 'edit'])->name('games.edit');
        Route::put('games/{game}', [adminController::class, 'update'])->name('games.update');
        Route::delete('games/{game}', [adminController::class, 'destroy'])->name('games.destroy');
        Route::resource('developers', DeveloperController::class);
        Route::resource('publishers', PublisherController::class);
    });
});
