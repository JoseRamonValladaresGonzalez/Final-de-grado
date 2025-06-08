@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 my-5">
    <h1 class="text-steam-accent mb-4">Orden #{{ $order->id }}</h1>

    <div class="card mb-4" style="background-color: var(--steam-blue); border:none;">
        <div class="card-body text-white">
            <p><strong>Fecha:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
            <p><strong>Total Pagado:</strong> €{{ number_format($order->total_amount, 2) }}</p>
        </div>
    </div>

    <div class="card mb-4" style="background-color: var(--steam-blue); border:none;">
        <div class="card-body">
            <h5 class="text-steam-accent mb-3">Detalles de la Orden</h5>
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle">
                    <thead>
                        <tr class="text-white">
                            <th>Juego</th>
                            <th>Cantidad</th>
                            <th>Claves de Steam</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr class="text-white">
                                <td>{{ $item->game->title }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    @foreach($item->steam_keys as $key)
                                        <span class="badge bg-secondary me-1">{{ $key }}</span>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <a href="{{ route('orders.index') }}" class="btn btn-outline-light">
        ← Volver al Historial
    </a>
</div>
@endsection
