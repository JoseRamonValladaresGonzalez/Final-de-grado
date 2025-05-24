<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = auth()->user()->cart()
            ->with(['developer', 'publisher'])
            ->withPivot('quantity')
            ->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->current_price * $item->pivot->quantity;
        });

        $totalDiscount = $cartItems->sum(function ($item) {
            return ($item->original_price - $item->current_price) * $item->pivot->quantity;
        });

        return view('cart.index', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'totalDiscount' => $totalDiscount,
            'total' => $subtotal
        ]);
    }

    public function store(Game $game)
    {
        auth()->user()->cart()->syncWithoutDetaching([
            $game->id => ['quantity' => 1]
        ]);

        return redirect()->route('cart.index')
            ->with('success', 'Juego añadido al carrito');
    }

   public function increment($pivotId)
{
    auth()->user()->cart()->updateExistingPivot($pivotId, [
        'quantity' => \DB::raw('quantity + 1')
    ]);

    return back()->with('success', 'Cantidad actualizada');
}

public function decrement($pivotId)
{
    $cartItem = auth()->user()->cart()->where('carts.id', $pivotId)->first();
    
    if(!$cartItem) {
        return back()->with('error', 'Item no encontrado');
    }

    if($cartItem->pivot->quantity > 1) {
        auth()->user()->cart()->updateExistingPivot($cartItem->id, [
            'quantity' => \DB::raw('quantity - 1')
        ]);
    } else {
        auth()->user()->cart()->detach($cartItem->id);
    }

    return back()->with('success', 'Cantidad actualizada');
}

public function destroy($pivotId)
{
    auth()->user()->cart()->detach($pivotId);
    return back()->with('success', 'Juego eliminado del carrito');
}
}