<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Developer;
use App\Models\Publisher;
use App\Models\GameFeature;
use App\Models\Tag;
use App\Models\SystemRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index()
    {
        $games = Game::with(['developer', 'publisher', 'tags'])->paginate(10);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        return view('admin.games.create', [
            'developers' => Developer::all(),
            'publishers' => Publisher::all(),
            'game_features' => GameFeature::all(),
            'tags' => Tag::all()
        ]);
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'developer_id' => 'required|exists:developers,id',
            'publisher_id' => 'required|exists:publishers,id',
            'release_date' => 'required|date',
            'original_price' => 'required|numeric|min:0',
            'current_price' => 'required|numeric|min:0',
            'discount_percent' => 'nullable|integer|min:0|max:100',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:4096',
            'screenshots' => 'nullable|array|max:5',
            'screenshots.*' => 'image|mimes:jpeg,png,jpg|max:4096',
            'game_features' => 'nullable|array',
            'game_features.*' => 'exists:game_features,id',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:255',
            'minimum_os' => 'required|string',
            'minimum_processor' => 'required|string',
            'minimum_memory' => 'required|string',
            'minimum_graphics' => 'required|string',
            'minimum_storage' => 'required|string',
            'minimum_directx'   => 'required|string',
            'recommended_directx' => 'required|string',
            'recommended_os' => 'required|string',
            'recommended_processor' => 'required|string',
            'recommended_memory' => 'required|string',
            'recommended_graphics' => 'required|string',
            'recommended_storage' => 'required|string'
        ]);


        DB::beginTransaction();

        $game = null; // inicializamos

        try {
            // 1) Creamos el juego sin imágenes
            $game = Game::create($request->except(['main_image', 'screenshots']));

            // 2) Procesamos la imagen principal
            if ($request->hasFile('main_image')) {
                $carpetaJuego = 'juegos/' . $game->id;
                $imagePath = $request->file('main_image')
                    ->store($carpetaJuego, 'public');
                $game->update(['main_image' => $imagePath]);
            }

            // 3) Procesamos capturas
            if ($request->hasFile('screenshots')) {
                foreach ($request->file('screenshots') as $key => $screenshot) {
                    $path = $screenshot->store("juegos/{$game->id}/screenshots", 'public');
                    $game->screenshots()->create([
                        'image_path'     => $path,
                        'order_position' => $key + 1,
                    ]);
                }
            }

            // 4) Características y etiquetas
            // (ajústalo si ya has hecho firstOrCreate…)
            $game->features()->createMany(
                collect($request->input('game_features', []))
                    ->map(fn($text) => ['feature_text' => $text])
                    ->toArray()
            );
            $tagIds = collect($request->input('tags', []))
                ->map(fn($name) => Tag::firstOrCreate(['tag_name' => $name])->id)
                ->toArray();
            $game->tags()->sync($tagIds);

            // 5) Requisitos
            $this->createSystemRequirements($game, $validated);

            DB::commit();

            return redirect()
                ->route('admin.games.index')
                ->with('success', 'Juego creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            // Solo borramos si $game fue creado
            if ($game && $game->id) {
                Storage::disk('public')->deleteDirectory("juegos/{$game->id}");
            }

            return back()
                ->withInput()
                ->withErrors([
                    'error' => 'Error al crear el juego: ' . $e->getMessage()
                ]);
        }
    }

    public function edit(Game $game)
    {
        return view('admin.games.edit', [
            'game' => $game->load(['game_features', 'tags', 'requirements']),
            'developers' => Developer::all(),
            'publishers' => Publisher::all(),
            'game_features' => GameFeature::all(),
            'tags' => Tag::all()
        ]);
    }

   public function update(Request $request, Game $game)
{
    $validated = $request->validate([
        'title'               => 'required|string|max:255',
        'description'         => 'required|string',
        'developer_id'        => 'required|exists:developers,id',
        'publisher_id'        => 'required|exists:publishers,id',
        'release_date'        => 'required|date',
        'original_price'      => 'required|numeric|min:0',
        'current_price'       => 'required|numeric|min:0',
        'discount_percent'    => 'nullable|integer|min:0|max:100',
        'main_image'          => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
        'screenshots'         => 'nullable|array|max:5',
        'screenshots.*'       => 'image|mimes:jpeg,png,jpg|max:4096',
        'features'            => 'nullable|array',
        'features.*'          => 'string|max:255',
        'tags'                => 'nullable|array',
        'tags.*'              => 'string|max:255',
        'minimum_os'          => 'required|string',
        'minimum_processor'   => 'required|string',
        'minimum_memory'      => 'required|string',
        'minimum_graphics'    => 'required|string',
        'minimum_directx'     => 'required|string',
        'minimum_storage'     => 'required|string',
        'recommended_os'      => 'required|string',
        'recommended_processor'=> 'required|string',
        'recommended_memory'  => 'required|string',
        'recommended_graphics'=> 'required|string',
        'recommended_directx' => 'required|string',
        'recommended_storage' => 'required|string',
    ]);

    DB::beginTransaction();

    try {
        // 1) Imagen principal
        if ($request->hasFile('main_image')) {
            Storage::disk('public')->delete($game->main_image);
            $path = $request->file('main_image')->store("juegos/{$game->id}", 'public');
            $validated['main_image'] = $path;
        }

        // 2) Actualizar datos básicos
        $game->update($validated);

        // 3) Nuevas capturas
        if ($request->hasFile('screenshots')) {
            foreach ($request->file('screenshots') as $key => $file) {
                $path = $file->store("juegos/{$game->id}/screenshots", 'public');
                $game->screenshots()->create([
                    'image_path'     => $path,
                    'order_position' => $game->screenshots()->count() + 1,
                ]);
            }
        }

        // 4) Características
        // Elimina las previas
        $game->game_features()->delete();
        // Crea las nuevas desde el input 'features[]'
        foreach ($request->input('features', []) as $text) {
            $game->game_features()->create([
                'feature_text' => $text,
            ]);
        }

        // 5) Etiquetas
        $tagIds = collect($request->input('tags', []))
            ->map(fn($name) => Tag::firstOrCreate(['tag_name' => $name])->id)
            ->toArray();
        $game->tags()->sync($tagIds);

        // 6) Requisitos del sistema
        $this->updateSystemRequirements($game, $validated);

        DB::commit();

        return redirect()
            ->route('admin.games.index')
            ->with('success', 'Juego actualizado exitosamente');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()
            ->withInput()
            ->withErrors(['error' => 'Error al actualizar el juego: ' . $e->getMessage()]);
    }
}

    public function destroy(Game $game)
    {
        DB::transaction(function () use ($game) {
            // Eliminar imágenes
            Storage::disk('public')->delete($game->main_image);
            Storage::disk('public')->deleteDirectory("juegos/{$game->id}");

            // Eliminar relaciones
            $game->game_features()->detach();
            $game->tags()->detach();
            $game->screenshots()->delete();
            $game->requirements()->delete();

            // Eliminar juego
            $game->delete();
        });

        return redirect()->route('admin.games.index')
            ->with('success', 'Juego eliminado exitosamente');
    }

    private function createSystemRequirements(Game $game, $data)
    {
        $game->requirements()->createMany([
            [
                'requirement_type' => 'minimum',
                'os' => $data['minimum_os'],
                'processor' => $data['minimum_processor'],
                'memory' => $data['minimum_memory'],
                'graphics' => $data['minimum_graphics'],
                'directx'   => $data['minimum_directx'],
                'storage' => $data['minimum_storage']
            ],
            [
                'requirement_type' => 'recommended',
                'os' => $data['recommended_os'],
                'processor' => $data['recommended_processor'],
                'memory' => $data['recommended_memory'],
                'graphics' => $data['recommended_graphics'],
                'directx'   => $data['recommended_directx'],
                'storage' => $data['recommended_storage']
            ]
        ]);
    }

    private function updateSystemRequirements(Game $game, $data)
    {
        $game->requirements()->where('requirement_type', 'minimum')->update([
            'os' => $data['minimum_os'],
            'processor' => $data['minimum_processor'],
            'memory' => $data['minimum_memory'],
            'graphics' => $data['minimum_graphics'],
            'directx'   => $data['minimum_directx'],
            'storage' => $data['minimum_storage']
        ]);

        $game->requirements()->where('requirement_type', 'recommended')->update([
            'os' => $data['recommended_os'],
            'processor' => $data['recommended_processor'],
            'memory' => $data['recommended_memory'],
            'graphics' => $data['recommended_graphics'],
            'directx'   => $data['recommended_directx'],
            'storage' => $data['recommended_storage']
        ]);
    }
}
