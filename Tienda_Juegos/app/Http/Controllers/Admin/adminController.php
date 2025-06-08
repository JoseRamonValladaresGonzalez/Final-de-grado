<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\GameFeature;
use App\Models\Tag;
use App\Models\Category;
use App\Models\SystemRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $games = Game::with(['developer', 'publisher', 'category', 'tags'])
                     ->paginate(10);

        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create', [
            'developers'    => Developer::all(),
            'publishers'    => Publisher::all(),
            'categories'    => Category::orderBy('name')->get(),
            'game_features' => GameFeature::all(),
            'tags'          => Tag::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'developer_id'     => 'required|exists:developers,id',
            'publisher_id'     => 'required|exists:publishers,id',
            'category_id'      => 'required|exists:categories,id',
            'release_date'     => 'required|date',
            'original_price'   => 'required|numeric|min:0',
            'current_price'    => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'main_image'       => 'required|image|mimes:jpeg,png,jpg|max:4096',
            'screenshots'      => 'nullable|array|max:5',
            'screenshots.*'    => 'image|mimes:jpeg,png,jpg|max:4096',
            'features'         => 'nullable|array',
            'features.*'       => 'string|max:255',
            'tags'             => 'nullable|array',
            'tags.*'           => 'string|max:255',
            'minimum_os'       => 'required|string',
            'minimum_processor'=> 'required|string',
            'minimum_memory'   => 'required|string',
            'minimum_graphics' => 'required|string',
            'minimum_directx'  => 'required|string',
            'minimum_storage'  => 'required|string',
            'recommended_os'       => 'required|string',
            'recommended_processor'=> 'required|string',
            'recommended_memory'   => 'required|string',
            'recommended_graphics' => 'required|string',
            'recommended_directx'  => 'required|string',
            'recommended_storage'  => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // 1) Crear registro de juego (sin imágenes aún)
            $game = Game::create([
                'title'            => $validated['title'],
                'description'      => $validated['description'],
                'developer_id'     => $validated['developer_id'],
                'publisher_id'     => $validated['publisher_id'],
                'category_id'      => $validated['category_id'],
                'release_date'     => $validated['release_date'],
                'original_price'   => $validated['original_price'],
                'current_price'    => $validated['current_price'],
                'discount_percent' => $validated['discount_percent'] ?? 0,
            ]);

            // 2) Imagen principal
            if ($request->hasFile('main_image')) {
                $path = $request->file('main_image')
                                ->store("juegos/{$game->id}", 'public');
                $game->update(['main_image' => $path]);
            }

            // 3) Screenshots
            if ($request->hasFile('screenshots')) {
                foreach ($request->file('screenshots') as $i => $img) {
                    $p = $img->store("juegos/{$game->id}/screenshots", 'public');
                    $game->screenshots()->create([
                        'image_path'     => $p,
                        'order_position' => $i + 1,
                    ]);
                }
            }

            // 4) Características
            $game->game_features()->createMany(
                collect($validated['features'] ?? [])
                    ->map(fn($t) => ['feature_text' => $t])
                    ->all()
            );

            // 5) Etiquetas
            $tagIds = collect($validated['tags'] ?? [])
                ->map(fn($name) => Tag::firstOrCreate(['tag_name' => $name])->id)
                ->all();
            $game->tags()->sync($tagIds);

            // 6) Requisitos
            $this->createSystemRequirements($game, $validated);

            DB::commit();

            return redirect()
                ->route('admin.games.index')
                ->with('success', 'Juego creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($game)) {
                Storage::disk('public')->deleteDirectory("juegos/{$game->id}");
            }
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', [
            'game'           => $game->load(['game_features', 'tags', 'requirements']),
            'developers'     => Developer::all(),
            'publishers'     => Publisher::all(),
            'categories'     => Category::orderBy('name')->get(),
            'game_features'  => GameFeature::all(),
            'tags'           => Tag::all(),
        ]);
    }

    public function update(Request $request, Game $game)
    {
        $validated = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'developer_id'     => 'required|exists:developers,id',
            'publisher_id'     => 'required|exists:publishers,id',
            'category_id'      => 'required|exists:categories,id',
            'release_date'     => 'required|date',
            'original_price'   => 'required|numeric|min:0',
            'current_price'    => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'main_image'       => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
            'screenshots'      => 'nullable|array|max:5',
            'screenshots.*'    => 'image|mimes:jpeg,png,jpg|max:4096',
            'features'         => 'nullable|array',
            'features.*'       => 'string|max:255',
            'tags'             => 'nullable|array',
            'tags.*'           => 'string|max:255',
            'minimum_os'       => 'required|string',
            'minimum_processor'=> 'required|string',
            'minimum_memory'   => 'required|string',
            'minimum_graphics' => 'required|string',
            'minimum_directx'  => 'required|string',
            'minimum_storage'  => 'required|string',
            'recommended_os'       => 'required|string',
            'recommended_processor'=> 'required|string',
            'recommended_memory'   => 'required|string',
            'recommended_graphics' => 'required|string',
            'recommended_directx'  => 'required|string',
            'recommended_storage'  => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            // 1) Imagen principal nueva
            if ($request->hasFile('main_image')) {
                Storage::disk('public')->delete($game->main_image);
                $path = $request->file('main_image')
                                ->store("juegos/{$game->id}", 'public');
                $validated['main_image'] = $path;
            }

            // 2) Actualizar datos básicos (incluye category_id)
            $game->update($validated);

            // 3) Nuevas capturas
            if ($request->hasFile('screenshots')) {
                foreach ($request->file('screenshots') as $i => $file) {
                    $p = $file->store("juegos/{$game->id}/screenshots", 'public');
                    $game->screenshots()->create([
                        'image_path'     => $p,
                        'order_position' => $game->screenshots()->count() + 1,
                    ]);
                }
            }

            // 4) Características: eliminar y re-crear
            $game->game_features()->delete();
            foreach ($validated['features'] ?? [] as $text) {
                $game->game_features()->create(['feature_text' => $text]);
            }

            // 5) Etiquetas
            $tagIds = collect($validated['tags'] ?? [])
                ->map(fn($name) => Tag::firstOrCreate(['tag_name' => $name])->id)
                ->all();
            $game->tags()->sync($tagIds);

            // 6) Requisitos
            $this->updateSystemRequirements($game, $validated);

            DB::commit();

            return redirect()
                ->route('admin.games.index')
                ->with('success', 'Juego actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Game $game)
    {
        DB::transaction(function () use ($game) {
            Storage::disk('public')->delete($game->main_image);
            Storage::disk('public')->deleteDirectory("juegos/{$game->id}");

            $game->game_features()->delete();
            $game->tags()->detach();
            $game->screenshots()->delete();
            $game->requirements()->delete();

            $game->delete();
        });

        return redirect()
            ->route('admin.games.index')
            ->with('success', 'Juego eliminado exitosamente');
    }

    private function createSystemRequirements(Game $game, array $data): void
    {
        $game->requirements()->createMany([
            [
                'requirement_type' => 'minimum',
                'os'                => $data['minimum_os'],
                'processor'         => $data['minimum_processor'],
                'memory'            => $data['minimum_memory'],
                'graphics'          => $data['minimum_graphics'],
                'directx'           => $data['minimum_directx'],
                'storage'           => $data['minimum_storage'],
            ],
            [
                'requirement_type' => 'recommended',
                'os'                => $data['recommended_os'],
                'processor'         => $data['recommended_processor'],
                'memory'            => $data['recommended_memory'],
                'graphics'          => $data['recommended_graphics'],
                'directx'           => $data['recommended_directx'],
                'storage'           => $data['recommended_storage'],
            ],
        ]);
    }

    private function updateSystemRequirements(Game $game, array $data): void
    {
        $game->requirements()
             ->where('requirement_type', 'minimum')
             ->update([
                 'os'        => $data['minimum_os'],
                 'processor' => $data['minimum_processor'],
                 'memory'    => $data['minimum_memory'],
                 'graphics'  => $data['minimum_graphics'],
                 'directx'   => $data['minimum_directx'],
                 'storage'   => $data['minimum_storage'],
             ]);

        $game->requirements()
             ->where('requirement_type', 'recommended')
             ->update([
                 'os'        => $data['recommended_os'],
                 'processor' => $data['recommended_processor'],
                 'memory'    => $data['recommended_memory'],
                 'graphics'  => $data['recommended_graphics'],
                 'directx'   => $data['recommended_directx'],
                 'storage'   => $data['recommended_storage'],
             ]);
    }
}
