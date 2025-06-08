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
    // 1) Obtenemos la fila del carrito (la relación) filtrando por el id de pivote
    $cartItem = auth()->user()->cart()
                    ->wherePivot('id', $pivotId)
                    ->first();

    // 2) Si existe, eliminamos la relación con el game_id correcto
    if ($cartItem) {
        auth()->user()->cart()->detach($cartItem->id);
        return back()->with('success', 'Juego eliminado del carrito');
    }

    // 3) Si no lo encontramos, devolvemos error
    return back()->with('error', 'No se encontró el item para eliminar');
}
}