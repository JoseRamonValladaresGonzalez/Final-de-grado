<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    // Asegurarse de que solo usuarios autenticados acceden:
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar el listado de pedidos del usuario logueado
     */
    public function index()
    {
        $user = auth()->user();

        // Cargo todas las órdenes del usuario, con paginación (10 por página)
        $orders = Order::withCount('orderItems')
                       ->where('user_id', $user->id)
                       ->latest()
                       ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Mostrar los detalles de una orden específica (si le pertenece)
     */
    public function show($id)
    {
        $user = auth()->user();

        // Busco la orden y me aseguro de que sea del usuario
        $order = Order::where('user_id', $user->id)
                      ->with(['orderItems.game'])
                      ->findOrFail($id);

        return view('orders.show', compact('order'));
    }
}
