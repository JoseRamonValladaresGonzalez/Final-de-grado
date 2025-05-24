@extends('layouts.app')

@section('content')
@if($errors->any())
<div class="alert alert-danger">
    <ul class="mb-0">
        @foreach($errors->all() as $err)
            <li>{{ $err }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="container-fluid px-4">
    <h1 class="mt-4">Editar Juego</h1>

    <form method="POST"
          action="{{ route('admin.games.update', $game) }}"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Columna Izquierda: Datos básicos -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Título -->
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="title"
                                   value="{{ old('title', $game->title) }}"
                                   class="form-control" required>
                        </div>

                        <!-- Descripción -->
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="5" required>{{ old('description', $game->description) }}</textarea>
                        </div>

                        <!-- Desarrollador / Editor -->
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Desarrollador</label>
                                <select name="developer_id"
                                        class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($developers as $dev)
                                        <option value="{{ $dev->id }}"
                                            {{ old('developer_id', $game->developer_id) == $dev->id ? 'selected' : '' }}>
                                            {{ $dev->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Editor</label>
                                <select name="publisher_id"
                                        class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($publishers as $pub)
                                        <option value="{{ $pub->id }}"
                                            {{ old('publisher_id', $game->publisher_id) == $pub->id ? 'selected' : '' }}>
                                            {{ $pub->name }}
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
                <div class="card mb-4">
                    <div class="card-body">
                        <!-- Imagen Principal -->
                        <div class="mb-3">
                            <label class="form-label">Imagen Principal</label>
                            <input type="file" name="main_image" class="form-control">
                            @if($game->main_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/'.$game->main_image) }}"
                                     class="img-thumbnail"
                                     style="max-height:150px">
                            </div>
                            @endif
                        </div>

                        <!-- Screenshots -->
                        <div class="mb-3">
                            <label class="form-label">Capturas (hasta 5)</label>
                            @for ($i = 0; $i < 5; $i++)
                                <input type="file" name="screenshots[]" class="form-control mb-1">
                            @endfor
                            <div class="form-text">Opcional</div>
                        </div>

                        <!-- Precios -->
                        <div class="mb-3">
                            <label class="form-label">Precio Original</label>
                            <input type="number" step="0.01" name="original_price"
                                   value="{{ old('original_price', $game->original_price) }}"
                                   class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio Actual</label>
                            <input type="number" step="0.01" name="current_price"
                                   value="{{ old('current_price', $game->current_price) }}"
                                   class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">% Descuento</label>
                            <input type="number" name="discount_percent"
                                   value="{{ old('discount_percent', $game->discount_percent) }}"
                                   class="form-control" min="0" max="100">
                        </div>

                        <!-- Fecha de Lanzamiento -->
                        <div class="mb-3">
                            <label class="form-label">Fecha de Lanzamiento</label>
                            <input type="date" name="release_date"
                                   value="{{ old('release_date', $game->release_date->format('Y-m-d')) }}"
                                   class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Características y Etiquetas -->
        <div class="row">
            <!-- Características -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <label class="form-label">Características</label>
                        <div id="features-wrapper">
                            @php
                                $features = old('features', $game->game_features->pluck('feature_text')->toArray());
                            @endphp
                            @forelse($features as $fv)
                                <div class="input-group mb-2">
                                    <input type="text" name="features[]"
                                           class="form-control"
                                           value="{{ $fv }}" required>
                                    <button class="btn btn-outline-danger remove-feature"
                                            type="button">&times;</button>
                                </div>
                            @empty
                                <div class="input-group mb-2">
                                    <input type="text" name="features[]"
                                           class="form-control"
                                           placeholder="Ingrese una característica" required>
                                    <button class="btn btn-outline-danger remove-feature"
                                            type="button">&times;</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="add-feature" class="btn btn-sm btn-steam">
                            + Agregar característica
                        </button>
                    </div>
                </div>
            </div>

            <!-- Etiquetas -->
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <label class="form-label">Etiquetas</label>
                        <div id="tags-wrapper">
                            @php
                                $tags = old('tags', $game->tags->pluck('tag_name')->toArray());
                            @endphp
                            @forelse($tags as $tv)
                                <div class="input-group mb-2">
                                    <input type="text" name="tags[]"
                                           class="form-control"
                                           value="{{ $tv }}" required>
                                    <button class="btn btn-outline-danger remove-tag"
                                            type="button">&times;</button>
                                </div>
                            @empty
                                <div class="input-group mb-2">
                                    <input type="text" name="tags[]"
                                           class="form-control"
                                           placeholder="Ingrese una etiqueta" required>
                                    <button class="btn btn-outline-danger remove-tag"
                                            type="button">&times;</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" id="add-tag" class="btn btn-sm btn-steam">
                            + Agregar etiqueta
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Requisitos del Sistema -->
        <div class="card mb-4">
            <div class="card-body">
                <h5>Requisitos del Sistema</h5>
                <div class="row">
                    @php
                        $reqMin = $game->requirements->where('requirement_type','minimum')->first() 
                                  ?? (object)['os'=>'','processor'=>'','memory'=>'','graphics'=>'','directx'=>'','storage'=>''];
                        $reqRec = $game->requirements->where('requirement_type','recommended')->first() 
                                  ?? (object)['os'=>'','processor'=>'','memory'=>'','graphics'=>'','directx'=>'','storage'=>''];
                    @endphp

                    <div class="col-md-6">
                        <h6>Mínimos</h6>
                        <input type="text" name="minimum_os" class="form-control mb-2"
                            placeholder="Sistema Operativo"
                            value="{{ old('minimum_os', $reqMin->os) }}" required>
                        <input type="text" name="minimum_processor" class="form-control mb-2"
                            placeholder="Procesador"
                            value="{{ old('minimum_processor', $reqMin->processor) }}" required>
                        <input type="text" name="minimum_memory" class="form-control mb-2"
                            placeholder="Memoria"
                            value="{{ old('minimum_memory', $reqMin->memory) }}" required>
                        <input type="text" name="minimum_graphics" class="form-control mb-2"
                            placeholder="Gráficos"
                            value="{{ old('minimum_graphics', $reqMin->graphics) }}" required>
                        <input type="text" name="minimum_directx" class="form-control mb-2"
                            placeholder="DirectX"
                            value="{{ old('minimum_directx', $reqMin->directx) }}" required>
                        <input type="text" name="minimum_storage" class="form-control"
                            placeholder="Almacenamiento"
                            value="{{ old('minimum_storage', $reqMin->storage) }}" required>
                    </div>

                    <div class="col-md-6">
                        <h6>Recomendados</h6>
                        <input type="text" name="recommended_os" class="form-control mb-2"
                            placeholder="Sistema Operativo"
                            value="{{ old('recommended_os', $reqRec->os) }}" required>
                        <input type="text" name="recommended_processor" class="form-control mb-2"
                            placeholder="Procesador"
                            value="{{ old('recommended_processor', $reqRec->processor) }}" required>
                        <input type="text" name="recommended_memory" class="form-control mb-2"
                            placeholder="Memoria"
                            value="{{ old('recommended_memory', $reqRec->memory) }}" required>
                        <input type="text" name="recommended_graphics" class="form-control mb-2"
                            placeholder="Gráficos"
                            value="{{ old('recommended_graphics', $reqRec->graphics) }}" required>
                        <input type="text" name="recommended_directx" class="form-control mb-2"
                            placeholder="DirectX"
                            value="{{ old('recommended_directx', $reqRec->directx) }}" required>
                        <input type="text" name="recommended_storage" class="form-control"
                            placeholder="Almacenamiento"
                            value="{{ old('recommended_storage', $reqRec->storage) }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-steam">Guardar Cambios</button>
            <a href="{{ route('admin.games.index') }}" class="btn btn-outline-light">Cancelar</a>
        </div>
    </form>
</div>

<!-- ... justo antes del cierre de </body> o al final de tu plantilla ... -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    function setupDynamic(wrapperSelector, addBtnSelector, removeClass) {
        var wrapper = document.querySelector(wrapperSelector);
        var btn     = document.querySelector(addBtnSelector);

        btn.addEventListener('click', function() {
            // Elegimos el name correcto
            var name = (wrapperSelector === '#features-wrapper') ? 'features[]' : 'tags[]';

            // Creamos el contenedor
            var group = document.createElement('div');
            group.className = 'input-group mb-2';

            // Y lo rellenamos concatenando cadenas
            group.innerHTML =
                '<input type="text" name="' + name + '" class="form-control" placeholder="Nuevo" required>' +
                '<button class="btn btn-outline-danger ' + removeClass + '" type="button">&times;</button>';

            wrapper.appendChild(group);
        });

        // Delegación de evento para eliminar
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
