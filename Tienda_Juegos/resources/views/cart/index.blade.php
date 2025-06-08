@extends('layouts.app')

@section('content')
<div class="container my-5">
    <h1 class="text-steam-accent mb-4">Carrito de Compras</h1>

    <div class="row gx-4">
        <!-- Lista de Items -->
        <div class="col-md-8">
            @forelse($cartItems as $item)
            <div class="cart-item text-white mb-4">
                <div class="d-flex align-items-center">
                    <!-- Portada pequeña -->
                    <img
                        src="{{ asset('storage/'.$item->main_image) }}"
                        class="game-cover-sm me-4"
                        alt="{{ $item->title }}">

                    <!-- Título + controles -->
                    <div class="flex-grow-1">
                        <h4 class="mb-1">{{ $item->title }}</h4>
                        <div class="d-flex align-items-center gap-3">
                            <!-- Eliminar -->
                            <form action="{{ route('cart.remove', $item->pivot->id) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm remove-link p-0">Eliminar</button>
                            </form>
                        </div>
                    </div>

                    <!-- Precios -->
                    <div class="text-end">
                        @php
                            $itemPrice = $item->current_price * $item->pivot->quantity;
                            $itemDiscount = ($item->original_price - $item->current_price) * $item->pivot->quantity;
                        @endphp
                        
                        @if($item->discount_percent > 0)
                        <div class="text-muted text-decoration-line-through">
                            ${{ number_format($item->original_price * $item->pivot->quantity, 2) }}
                        </div>
                        @endif

                        <div class="price-highlight">
                            ${{ number_format($itemPrice, 2) }}
                        </div>
                        
                        @if($itemDiscount > 0)
                        <div class="text-success">
                            Ahorras ${{ number_format($itemDiscount, 2) }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="text-white">Tu carrito está vacío.</p>
            @endforelse
        </div>

        <!-- Resumen del Carrito -->
        <div class="col-md-4">
            @php
            $subtotal = $cartItems->sum(function($item) {
                return $item->current_price * $item->pivot->quantity;
            });
            
            $totalDiscount = $cartItems->sum(function($item) {
                return ($item->original_price - $item->current_price) * $item->pivot->quantity;
            });
            
            $total = $subtotal;
            @endphp

            <div class="cart-summary text-white">
                <h4 class="text-steam-accent mb-4">Resumen de Compra</h4>

                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal:</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>

                @if($totalDiscount > 0)
                <div class="d-flex justify-content-between mb-2 text-success">
                    <span>Descuentos:</span>
                    <span>-${{ number_format($totalDiscount, 2) }}</span>
                </div>
                @endif

                <hr class="border-secondary my-3">

                <div class="d-flex justify-content-between mb-4 fw-bold">
                    <span>Total:</span>
                    <span class="price-highlight">${{ number_format($total, 2) }}</span>
                </div>

                <form action="{{ route('stripe.checkout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary">Pagar con Stripe</button>
                </form>

                <div class="mt-4 text-center">
                    <a href="{{ route('home') }}" class="text-muted text-decoration-none">
                        ← Seguir comprando
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection