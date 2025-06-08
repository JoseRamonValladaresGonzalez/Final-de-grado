<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Models\Game;
use App\Models\Order;
use App\Models\OrderItem;
use App\Mail\SteamKeyMail;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class StripeController extends Controller
{
    /**
     * Mostrar la vista de checkout.
     */
    public function checkout()
    {
        $user = auth()->user();

        // Validar carrito no vacío
        if ($user->cart()->count() === 0) {
            return redirect()->route('cart.index')
                ->with('error', 'El carrito está vacío');
        }

        // Configurar Stripe
        Stripe::setApiKey(config('stripe.sk'));

        try {
            // Crear session de Stripe con los items del carrito
            $lineItems = $user->cart->map(function ($item) {
                return [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item->title,
                        ],
                        'unit_amount' => (int) round($item->current_price * 100),
                    ],
                    'quantity' => $item->pivot->quantity,
                ];
            })->toArray();

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items'           => $lineItems,
                'mode'                 => 'payment',
                'success_url'          => route('stripe.success'),
                'cancel_url'           => route('cart.index'),
                'metadata'             => [
                    'user_id' => $user->id
                ],
            ]);

            return redirect()->away($session->url);
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Error al procesar el pago: ' . $e->getMessage());
        }
    }

    /**
     * Página mostrada después del pago exitoso: crea la orden,
     * genera las claves, envía correos y vacía el carrito.
     */
    public function success()
    {
        $user = auth()->user();

        // 1) Recuperamos los ítems del carrito con su cantidad
        $cartItems = $user->cart()->withPivot('quantity')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')
                ->with('error', 'No tienes juegos en el carrito.');
        }

        // 2) Calculamos totales (total_amount y discount_amount)
        $totalAmount    = 0;
        $discountAmount = 0;
        foreach ($cartItems as $item) {
            $subtotal       = $item->current_price * $item->pivot->quantity;
            $totalAmount   += $subtotal;
            $discountAmount += ($item->original_price - $item->current_price) * $item->pivot->quantity;
        }

        // 3) Dentro de transacción, guardamos la orden y sus ítems
        DB::beginTransaction();
        try {
            // 3a) Crear la fila en orders
            $order = Order::create([
                'user_id'         => $user->id,
                'total_amount'    => $totalAmount,
                'discount_amount' => $discountAmount,
                'stripe_session_id' => null, // opcional si quieres guardar el ID de Stripe
            ]);

            $allKeys = []; // acumulador de todas las claves

            // 3b) Por cada ítem del carrito, crear un order_item y generar N claves
            foreach ($cartItems as $item) {
                // Crear el OrderItem sin steam_keys aún
                $orderItem = OrderItem::create([
                    'order_id'         => $order->id,
                    'game_id'          => $item->id,
                    'quantity'         => $item->pivot->quantity,
                    'unit_price'       => $item->current_price,
                    'discount_percent' => $item->discount_percent,
                    'steam_keys'       => null,
                ]);

                // Generar tantas claves como cantidad y enviarlas por correo
                $keysArray = [];
                for ($i = 0; $i < $item->pivot->quantity; $i++) {
                    $newKey = Str::upper(Str::random(16));
                    $keysArray[] = $newKey;
                    $allKeys[] = $newKey;

                    // Enviar cada clave al usuario
                    Mail::to($user->email)->send(new SteamKeyMail($newKey));
                }

                // Guardar el array de claves en JSON
                $orderItem->steam_keys = $keysArray;
                $orderItem->save();
            }

            // 3c) Vaciar carrito
            $user->cart()->detach();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('cart.index')
                ->with('error', 'Hubo un error al procesar tu pedido.');
        }


        return view('stripe.success', [
            'steamKeys' => $allKeys
        ]);
    }
}
