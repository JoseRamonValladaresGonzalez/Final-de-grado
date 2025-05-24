<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\User;
use App\Models\Game;
use App\Models\Order;


class DashboardController extends Controller
{
    public function index()
    {
        $gameCount = Game::count(); // Obtener el conteo aquí
        
        return view('admin.dashboard.index', [
            'gameCount' => $gameCount // Pasarlo a la vista
        ]);
    }


}
