<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Mostrar la tienda con:
     *  - filtrado por categoría (opcional)
     *  - búsqueda por título (opcional, via ?q=)
     *  - historial de búsquedas en sesión
     */
     public function index(Request $request, int $categoryId = null)
    {
        // Recupera todas las categorías para el dropdown
        $categories = Category::orderBy('name')->get();

        // Lee el término de búsqueda
        $search = trim($request->input('q', ''));

        // Construye la query para destacados y lista
        $featuredQuery = Game::with(['developer','publisher','category'])
                              ->where('discount_percent','>',20);
        $listQuery     = Game::with(['developer','publisher','category']);

        // Si hay búsqueda, añade el filtro
        if ($search !== '') {
            $featuredQuery->where('title', 'like', "%{$search}%");
            $listQuery    ->where('title', 'like', "%{$search}%");

            // Guarda en el historial de sesión (único por término, máximo 10)
            $history = session()->get('search_history', []);
            if (! in_array($search, $history)) {
                array_unshift($history, $search);
                session()->put('search_history', array_slice($history, 0, 10));
            }
        }

        // Si hay categoría, añade el filtro
        if ($categoryId) {
            $featuredQuery->where('category_id', $categoryId);
            $listQuery    ->where('category_id', $categoryId);
        }

        // Obtén resultados
        $featuredGames = $featuredQuery->latest()->take(3)->get();

        // Ahora paginamos **10 juegos por página** para “Todos los Juegos”
        $games = $listQuery->latest()->paginate(10)->withQueryString();

        // Recupera el historial actual
        $searchHistory = session()->get('search_history', []);

        return view('home', compact(
            'categories',
            'categoryId',
            'search',
            'searchHistory',
            'featuredGames',
            'games'
        ));
    }
}
