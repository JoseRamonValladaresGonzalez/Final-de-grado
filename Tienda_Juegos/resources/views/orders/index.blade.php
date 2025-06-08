@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 my-5">
    <h1 class="text-steam-accent mb-4">Historial de Compras</h1>

    @if($orders->isEmpty())
        <div class="alert alert-info" style="background-color: var(--steam-blue); border:none; color:white;">
            No has realizado ninguna compra aún.
        </div>
    @else
        <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-striped align-middle">
                        <thead>
                            <tr class="text-white">
                                <th># Orden</th>
                                <th>Fecha</th>
                                <th>Items</th>
                                <th>Total (€)</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr class="text-white">
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $order->orderItems->count() }}</td>
                                    <td>{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('orders.show', $order) }}"
                                           class="btn-steam btn-sm">
                                            <i class="fas fa-eye me-1"></i>Ver Detalle
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="d-flex justify-content-center mt-3">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
