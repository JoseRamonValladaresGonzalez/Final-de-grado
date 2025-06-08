@extends('layouts.admin')

@section('content')
@if($errors->any())
<div class="alert alert-danger bg-steam-blue text-white">
    <ul class="mb-0">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4 text-steam-accent">Editar Juego</h1>

    <form method="POST"
          action="{{ route('admin.games.update', $game) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Columna Izquierda: Datos básicos -->
            <div class="col-md-8">
                <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                    <div class="card-body">
                        {{-- Título --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Título</label>
                            <input type="text" name="title"
                                   value="{{ old('title', $game->title) }}"
                                   class="form-control bg-light text-dark" required>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Descripción</label>
                            <textarea name="description"
                                      class="form-control bg-light text-dark"
                                      rows="5" required>{{ old('description', $game->description) }}</textarea>
                        </div>

                        {{-- Desarrollador / Editor / Categoría --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label text-white">Desarrollador</label>
                                <select name="developer_id" class="form-select bg-light text-dark" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($developers as $dev)
                                        <option value="{{ $dev->id }}"
                                            {{ old('developer_id', $game->developer_id) == $dev->id ? 'selected' : '' }}>
                                            {{ $dev->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-white">Editor</label>
                                <select name="publisher_id" class="form-select bg-light text-dark" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($publishers as $pub)
                                        <option value="{{ $pub->id }}"
                                            {{ old('publisher_id', $game->publisher_id) == $pub->id ? 'selected' : '' }}>
                                            {{ $pub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-white">Categoría</label>
                                <select name="category_id" class="form-select bg-light text-dark" required>
                                    <option value="">-- Selecciona una categoría --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id', $game->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Columna Derecha: Imagen y precio -->
            <div class="col-md-4">
                <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                    <div class="card-body">
                        {{-- Imagen Principal --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Imagen Principal</label>
                            <input type="file" name="main_image" class="form-control bg-light text-dark">
                            @if($game->main_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$game->main_image) }}"
                                     class="img-thumbnail"
                                     style="max-height:150px">
                            </div>
                            @endif
                        </div>

                        {{-- Screenshots --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Capturas (hasta 5)</label>
                            @for ($i = 0; $i < 5; $i++)
                                <input type="file"
                                       name="screenshots[]"
                                       class="form-control mb-1 bg-light text-dark">
                            @endfor
                            <div class="form-text text-white">Opcional</div>
                        </div>

                        {{-- Precios --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Precio Original</label>
                            <input type="number" step="0.01" name="original_price"
                                   value="{{ old('original_price', $game->original_price) }}"
                                   class="form-control bg-light text-dark" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Precio Actual</label>
                            <input type="number" step="0.01" name="current_price"
                                   value="{{ old('current_price', $game->current_price) }}"
                                   class="form-control bg-light text-dark" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">% Descuento</label>
                            <input type="number" name="discount_percent"
                                   value="{{ old('discount_percent', $game->discount_percent) }}"
                                   class="form-control bg-light text-dark" min="0" max="100">
                        </div>

                        {{-- Fecha de Lanzamiento --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Fecha de Lanzamiento</label>
                            <input type="date" name="release_date"
                                   value="{{ old('release_date', $game->release_date->format('Y-m-d')) }}"
                                   class="form-control bg-light text-dark" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Características -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                    <div class="card-body">
                        <label class="form-label text-white">Características</label>
                        <div id="features-wrapper">
                            @php
                                $features = old('features', $game->game_features->pluck('feature_text')->toArray());
                            @endphp
                            @forelse($features as $fv)
                                <div class="input-group mb-2">
                                    <input type="text" name="features[]"
                                           class="form-control bg-light text-dark"
                                           value="{{ $fv }}" required>
                                    <button class="btn btn-outline-danger remove-feature" type="button">&times;</button>
                                </div>
                            @empty
                                <div class="input-group mb-2">
                                    <input type="text" name="features[]"
                                           class="form-control bg-light text-dark"
                                           placeholder="Ingrese una característica" required>
                                    <button class="btn btn-outline-danger remove-feature" type="button">&times;</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="add-feature" class="btn-steam btn-sm">
                            + Agregar característica
                        </button>
                    </div>
                </div>
            </div>

            <!-- Etiquetas -->
            <div class="col-md-6">
                <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                    <div class="card-body">
                        <label class="form-label text-white">Etiquetas</label>
                        <div id="tags-wrapper">
                            @php
                                $tags = old('tags', $game->tags->pluck('tag_name')->toArray());
                            @endphp
                            @forelse($tags as $tv)
                                <div class="input-group mb-2">
                                    <input type="text" name="tags[]"
                                           class="form-control bg-light text-dark"
                                           value="{{ $tv }}" required>
                                    <button class="btn btn-outline-danger remove-tag" type="button">&times;</button>
                                </div>
                            @empty
                                <div class="input-group mb-2">
                                    <input type="text" name="tags[]"
                                           class="form-control bg-light text-dark"
                                           placeholder="Ingrese una etiqueta" required>
                                    <button class="btn btn-outline-danger remove-tag" type="button">&times;</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="add-tag" class="btn-steam btn-sm">
                            + Agregar etiqueta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requisitos del Sistema -->
        <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
            <div class="card-body">
                <h5 class="text-steam-accent">Requisitos del Sistema</h5>
                <div class="row">
                    @php
                        $reqMin = $game->requirements->where('requirement_type','minimum')->first() 
                                  ?? (object)['os'=>'','processor'=>'','memory'=>'','graphics'=>'','directx'=>'','storage'=>''];
                        $reqRec = $game->requirements->where('requirement_type','recommended')->first() 
                                  ?? (object)['os'=>'','processor'=>'','memory'=>'','graphics'=>'','directx'=>'','storage'=>''];
                    @endphp
                    <div class="col-md-6">
                        <h6 class="text-steam-accent">Mínimos</h6>
                        <input type="text" name="minimum_os" class="form-control mb-2 bg-light text-dark" placeholder="Sistema Operativo" value="{{ old('minimum_os', $reqMin->os) }}" required>
                        <input type="text" name="minimum_processor" class="form-control mb-2 bg-light text-dark" placeholder="Procesador" value="{{ old('minimum_processor', $reqMin->processor) }}" required>
                        <input type="text" name="minimum_memory" class="form-control mb-2 bg-light text-dark" placeholder="Memoria" value="{{ old('minimum_memory', $reqMin->memory) }}" required>
                        <input type="text" name="minimum_graphics" class="form-control mb-2 bg-light text-dark" placeholder="Gráficos" value="{{ old('minimum_graphics', $reqMin->graphics) }}" required>
                        <input type="text" name="minimum_directx" class="form-control mb-2 bg-light text-dark" placeholder="DirectX" value="{{ old('minimum_directx', $reqMin->directx) }}" required>
                        <input type="text" name="minimum_storage" class="form-control bg-light text-dark" placeholder="Almacenamiento" value="{{ old('minimum_storage', $reqMin->storage) }}" required>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-steam-accent">Recomendados</h6>
                        <input type="text" name="recommended_os" class="form-control mb-2 bg-light text-dark" placeholder="Sistema Operativo" value="{{ old('recommended_os', $reqRec->os) }}" required>
                        <input type="text" name="recommended_processor" class="form-control mb-2 bg-light text-dark" placeholder="Procesador" value="{{ old('recommended_processor', $reqRec->processor) }}" required>
                        <input type="text" name="recommended_memory" class="form-control mb-2 bg-light text-dark" placeholder="Memoria" value="{{ old('recommended_memory', $reqRec->memory) }}" required>
                        <input type="text" name="recommended_graphics" class="form-control mb-2 bg-light text-dark" placeholder="Gráficos" value="{{ old('recommended_graphics', $reqRec->graphics) }}" required>
                        <input type="text" name="recommended_directx" class="form-control mb-2 bg-light text-dark" placeholder="DirectX" value="{{ old('recommended_directx', $reqRec->directx) }}" required>
                        <input type="text" name="recommended_storage" class="form-control bg-light text-dark" placeholder="Almacenamiento" value="{{ old('recommended_storage', $reqRec->storage) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn-steam">Guardar Cambios</button>
            <a href="{{ route('admin.games.index') }}" class="btn btn-outline-light">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupDynamic(wrapperSelector, addBtnSelector, removeClass) {
        var wrapper = document.querySelector(wrapperSelector);
        var btn     = document.querySelector(addBtnSelector);
        btn.addEventListener('click', function() {
            var name = (wrapperSelector === '#features-wrapper') ? 'features[]' : 'tags[]';
            var group = document.createElement('div');
            group.className = 'input-group mb-2';
            group.innerHTML =
                '<input type="text" name="' + name + '" class="form-control bg-light text-dark" placeholder="Nuevo" required>' +
                '<button class="btn btn-outline-danger ' + removeClass + '" type="button">&times;</button>';
            wrapper.appendChild(group);
        });
        wrapper.addEventListener('click', function(e) {
            if (e.target.classList.contains(removeClass)) {
                e.target.closest('.input-group').remove();
            }
        });
    }
    setupDynamic('#features-wrapper', '#add-feature', 'remove-feature');
    setupDynamic('#tags-wrapper',     '#add-tag',     'remove-tag');
});
</script>
@endsection
