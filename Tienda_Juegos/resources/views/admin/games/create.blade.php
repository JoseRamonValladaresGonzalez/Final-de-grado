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
    <h1 class="mt-4 text-steam-accent">Crear Juego</h1>

    <form method="POST"
          action="{{ route('admin.games.store') }}"
          enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Columna Izquierda: Datos básicos -->
            <div class="col-md-8">
                <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                    <div class="card-body">
                        {{-- Título --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Título</label>
                            <input type="text" name="title"
                                   value="{{ old('title') }}"
                                   class="form-control bg-light text-dark" required>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Descripción</label>
                            <textarea name="description"
                                      class="form-control bg-light text-dark"
                                      rows="5" required>{{ old('description') }}</textarea>
                        </div>

                        {{-- Desarrollador / Editor / Categoría --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-4">
                                <label class="form-label text-white">Desarrollador</label>
                                <select name="developer_id"
                                        class="form-select bg-light text-dark"
                                        required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($developers as $dev)
                                        <option value="{{ $dev->id }}"
                                            {{ old('developer_id') == $dev->id ? 'selected' : '' }}>
                                            {{ $dev->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-white">Editor</label>
                                <select name="publisher_id"
                                        class="form-select bg-light text-dark"
                                        required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($publishers as $pub)
                                        <option value="{{ $pub->id }}"
                                            {{ old('publisher_id') == $pub->id ? 'selected' : '' }}>
                                            {{ $pub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label text-white">Categoría</label>
                                <select name="category_id"
                                        class="form-select bg-light text-dark"
                                        required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
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
                            <input type="file" name="main_image"
                                   class="form-control bg-light text-dark" required>
                        </div>

                        {{-- Screenshots --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Capturas (hasta 5)</label>
                            @for($i = 0; $i < 5; $i++)
                                <input type="file" name="screenshots[]"
                                       class="form-control mb-1 bg-light text-dark">
                            @endfor
                            <div class="form-text text-white">Opcional</div>
                        </div>

                        {{-- Precios --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Precio Original</label>
                            <input type="number" step="0.01" name="original_price"
                                   value="{{ old('original_price') }}"
                                   class="form-control bg-light text-dark" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">Precio Actual</label>
                            <input type="number" step="0.01" name="current_price"
                                   value="{{ old('current_price') }}"
                                   class="form-control bg-light text-dark" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white">% Descuento</label>
                            <input type="number" name="discount_percent"
                                   value="{{ old('discount_percent', 0) }}"
                                   class="form-control bg-light text-dark"
                                   min="0" max="100">
                        </div>

                        {{-- Fecha de Lanzamiento --}}
                        <div class="mb-3">
                            <label class="form-label text-white">Fecha de Lanzamiento</label>
                            <input type="date" name="release_date"
                                   value="{{ old('release_date') }}"
                                   class="form-control bg-light text-dark" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Características y Etiquetas -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                    <div class="card-body">
                        <label class="form-label text-white">Características</label>
                        <div id="features-wrapper">
                            {{-- Si ya había old() --}}
                            @if(old('features'))
                                @foreach(old('features') as $fv)
                                    <div class="input-group mb-2">
                                        <input type="text" name="features[]"
                                               value="{{ $fv }}"
                                               class="form-control bg-light text-dark" required>
                                        <button class="btn btn-outline-danger remove-feature"
                                                type="button">&times;</button>
                                    </div>
                                @endforeach
                            @else
                                {{-- Un campo vacío inicial --}}
                                <div class="input-group mb-2">
                                    <input type="text" name="features[]"
                                           placeholder="Ingrese una característica"
                                           class="form-control bg-light text-dark" required>
                                    <button class="btn btn-outline-danger remove-feature"
                                            type="button">&times;</button>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="add-feature" class="btn-steam btn-sm">
                            + Agregar característica
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                    <div class="card-body">
                        <label class="form-label text-white">Etiquetas</label>
                        <div id="tags-wrapper">
                            @if(old('tags'))
                                @foreach(old('tags') as $tv)
                                    <div class="input-group mb-2">
                                        <input type="text" name="tags[]"
                                               value="{{ $tv }}"
                                               class="form-control bg-light text-dark" required>
                                        <button class="btn btn-outline-danger remove-tag"
                                                type="button">&times;</button>
                                    </div>
                                @endforeach
                            @else
                                <div class="input-group mb-2">
                                    <input type="text" name="tags[]"
                                           placeholder="Ingrese una etiqueta"
                                           class="form-control bg-light text-dark" required>
                                    <button class="btn btn-outline-danger remove-tag"
                                            type="button">&times;</button>
                                </div>
                            @endif
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
                    <div class="col-md-6">
                        <h6 class="text-steam-accent">Mínimos</h6>
                        <input type="text" name="minimum_os" class="form-control mb-2 bg-light text-dark"
                               placeholder="Sistema Operativo" value="{{ old('minimum_os') }}" required>
                        <input type="text" name="minimum_processor" class="form-control mb-2 bg-light text-dark"
                               placeholder="Procesador" value="{{ old('minimum_processor') }}" required>
                        <input type="text" name="minimum_memory" class="form-control mb-2 bg-light text-dark"
                               placeholder="Memoria" value="{{ old('minimum_memory') }}" required>
                        <input type="text" name="minimum_graphics" class="form-control mb-2 bg-light text-dark"
                               placeholder="Gráficos" value="{{ old('minimum_graphics') }}" required>
                        <input type="text" name="minimum_directx" class="form-control mb-2 bg-light text-dark"
                               placeholder="DirectX" value="{{ old('minimum_directx') }}" required>
                        <input type="text" name="minimum_storage" class="form-control bg-light text-dark"
                               placeholder="Almacenamiento" value="{{ old('minimum_storage') }}" required>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-steam-accent">Recomendados</h6>
                        <input type="text" name="recommended_os" class="form-control mb-2 bg-light text-dark"
                               placeholder="Sistema Operativo" value="{{ old('recommended_os') }}" required>
                        <input type="text" name="recommended_processor" class="form-control mb-2 bg-light text-dark"
                               placeholder="Procesador" value="{{ old('recommended_processor') }}" required>
                        <input type="text" name="recommended_memory" class="form-control mb-2 bg-light text-dark"
                               placeholder="Memoria" value="{{ old('recommended_memory') }}" required>
                        <input type="text" name="recommended_graphics" class="form-control mb-2 bg-light text-dark"
                               placeholder="Gráficos" value="{{ old('recommended_graphics') }}" required>
                        <input type="text" name="recommended_directx" class="form-control mb-2 bg-light text-dark"
                               placeholder="DirectX" value="{{ old('recommended_directx') }}" required>
                        <input type="text" name="recommended_storage" class="form-control bg-light text-dark"
                               placeholder="Almacenamiento" value="{{ old('recommended_storage') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn-steam">Crear Juego</button>
            <a href="{{ route('admin.games.index') }}" class="btn btn-outline-light">Cancelar</a>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    function setupDynamic(wrapperSelector, addBtnSelector, removeClass) {
        const wrapper = document.querySelector(wrapperSelector);
        document.querySelector(addBtnSelector)
                .addEventListener('click', () => {
            const name = (wrapperSelector === '#features-wrapper')
                          ? 'features[]' : 'tags[]';
            const group = document.createElement('div');
            group.className = 'input-group mb-2';
            group.innerHTML =
                `<input type="text" name="${name}" class="form-control bg-light text-dark" placeholder="Nuevo" required>` +
                `<button class="btn btn-outline-danger ${removeClass}" type="button">&times;</button>`;
            wrapper.appendChild(group);
        });
        wrapper.addEventListener('click', e => {
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
