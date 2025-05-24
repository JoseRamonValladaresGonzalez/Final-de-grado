<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Stripe\Checkout\Session;
use Stripe\Exception\ApiErrorException;
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
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('stripe.success'),
            'cancel_url' => route('cart.index'),
            'metadata' => [
                'user_id' => $user->id
            ]
        ]);
        
        return redirect()->away($session->url);
        
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error al procesar el pago: ' . $e->getMessage());
    }
}
    /**
     * Crear sesión de pago con Stripe (modo prueba).
     * Redirige al usuario al checkout de Stripe.
     */
    public function test(): RedirectResponse
    {
        // Establece la clave secreta de Stripe desde config/stripe.php
        Stripe::setApiKey(config('stripe.sk'));

        $session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'gbp',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount'  => 500, // £5.00
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('success'),
            'cancel_url'  => route('checkout'),
        ]);

        return redirect()->away($session->url);
    }

    /**
     * Crear sesión de pago con Stripe en modo live (si es necesario).
     */
    public function live(): RedirectResponse
    {
        // Si más adelante agregas STRIPE_LIVE_SK en el .env
        Stripe::setApiKey(config('stripe.live_sk'));

        $session = Session::create([
            'line_items' => [[
                'price_data' => [
                    'currency'     => 'gbp',
                    'product_data' => [
                        'name' => 'T-shirt',
                    ],
                    'unit_amount'  => 500,
                ],
                'quantity' => 1,
            ]],
            'mode'        => 'payment',
            'success_url' => route('success'),
            'cancel_url'  => route('checkout'),
        ]);

        return redirect()->away($session->url);
    }

    /**
     * Página mostrada después del pago exitoso.
     */
    public function success(): View|Factory|Application
    {
        return view('stripe.success');
    }

    /**
     * Página de inicio o índice de Stripe.
     */
    public function index(): View|Factory|Application
    {
        return view('stripe.index'); // opcional
    }
}
