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
    <h1 class="mt-4">Crear Juego</h1>

    <form method="POST"
        action="{{ route('admin.games.store') }}"
        enctype="multipart/form-data">
        @csrf

        <div class="row">
            <!-- Columna Izquierda: Datos básicos -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-body">
                        {{-- Título --}}
                        <div class="mb-3">
                            <label class="form-label">Título</label>
                            <input type="text" name="title"
                                value="{{ old('title') }}"
                                class="form-control" required>
                        </div>

                        {{-- Descripción --}}
                        <div class="mb-3">
                            <label class="form-label">Descripción</label>
                            <textarea name="description"
                                class="form-control"
                                rows="5" required>{{ old('description') }}</textarea>
                        </div>

                        {{-- Desarrollador / Editor --}}
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Desarrollador</label>
                                <select name="developer_id"
                                    class="form-select" required>
                                    <option value="">-- Selecciona --</option>
                                    @foreach($developers as $dev)
                                    <option value="{{ $dev->id }}"
                                        {{ old('developer_id') == $dev->id ? 'selected' : '' }}>
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
                                        {{ old('publisher_id') == $pub->id ? 'selected' : '' }}>
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

                        {{-- Imagen Principal --}}
                        <div class="mb-3">
                            <label class="form-label">Imagen Principal</label>
                            <input type="file" name="main_image"
                                class="form-control" required>
                        </div>

                        {{-- Screenshots --}}
                        <div class="mb-3">
                            <label class="form-label">Capturas (hasta 5)</label>
                            <input type="file" name="screenshots[]"
                                class="form-control mb-1">
                            <input type="file" name="screenshots[]"
                                class="form-control mb-1">
                            <input type="file" name="screenshots[]"
                                class="form-control mb-1">
                            <input type="file" name="screenshots[]"
                                class="form-control mb-1">
                            <input type="file" name="screenshots[]"
                                class="form-control">
                            <div class="form-text">Opcional</div>
                        </div>

                        {{-- Precios --}}
                        <div class="mb-3">
                            <label class="form-label">Precio Original</label>
                            <input type="number" step="0.01" name="original_price"
                                value="{{ old('original_price') }}"
                                class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Precio Actual</label>
                            <input type="number" step="0.01" name="current_price"
                                value="{{ old('current_price') }}"
                                class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">% Descuento</label>
                            <input type="number" name="discount_percent"
                                value="{{ old('discount_percent', 0) }}"
                                class="form-control" min="0" max="100">
                        </div>

                        {{-- Fecha de Lanzamiento --}}
                        <div class="mb-3">
                            <label class="form-label">Fecha de Lanzamiento</label>
                            <input type="date" name="release_date"
                                value="{{ old('release_date') }}"
                                class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Características y Etiquetas -->
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <label class="form-label">Características</label>
                        <div id="features-wrapper">
                            @if(old('features'))
                            @foreach(old('features') as $fv)
                            <div class="input-group mb-2">
                                <input type="text" name="features[]"
                                    class="form-control"
                                    value="{{ $fv }}" required>
                                <button class="btn btn-outline-danger remove-feature" type="button">&times;</button>
                            </div>
                            @endforeach
                            @else
                            {{-- Un campo vacío por defecto --}}
                            <div class="input-group mb-2">
                                <input type="text" name="features[]"
                                    class="form-control"
                                    placeholder="Ingrese una característica" required>
                                <button class="btn btn-outline-danger remove-feature" type="button">&times;</button>
                            </div>
                            @endif
                        </div>
                        <button type="button" id="add-feature" class="btn btn-sm btn-steam">
                            + Agregar característica
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <label class="form-label">Etiquetas</label>
                        <div id="tags-wrapper">
                            @if(old('tags'))
                            @foreach(old('tags') as $tv)
                            <div class="input-group mb-2">
                                <input type="text" name="tags[]"
                                    class="form-control"
                                    value="{{ $tv }}" required>
                                <button class="btn btn-outline-danger remove-tag" type="button">&times;</button>
                            </div>
                            @endforeach
                            @else
                            <div class="input-group mb-2">
                                <input type="text" name="tags[]"
                                    class="form-control"
                                    placeholder="Ingrese una etiqueta" required>
                                <button class="btn btn-outline-danger remove-tag" type="button">&times;</button>
                            </div>
                            @endif
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
                    <div class="col-md-6">
                        <h6>Mínimos</h6>
                        <input type="text" name="minimum_os" class="form-control mb-2" placeholder="Sistema Operativo" value="{{ old('minimum_os') }}" required>
                        <input type="text" name="minimum_processor" class="form-control mb-2" placeholder="Procesador" value="{{ old('minimum_processor') }}" required>
                        <input type="text" name="minimum_memory" class="form-control mb-2" placeholder="Memoria" value="{{ old('minimum_memory') }}" required>
                        <input type="text" name="minimum_graphics" class="form-control mb-2" placeholder="Gráficos" value="{{ old('minimum_graphics') }}" required>
                        <input type="text" name="minimum_storage" class="form-control" placeholder="Almacenamiento" value="{{ old('minimum_storage') }}" required>
                        <input type="text" name="minimum_directx" class="form-control mb-2" placeholder="DirectX" value="{{ old('minimum_directx') }}" required>
                    </div>
                    <div class="col-md-6">
                        <h6>Recomendados</h6>
                        <input type="text" name="recommended_os" class="form-control mb-2" placeholder="Sistema Operativo" value="{{ old('recommended_os') }}" required>
                        <input type="text" name="recommended_processor" class="form-control mb-2" placeholder="Procesador" value="{{ old('recommended_processor') }}" required>
                        <input type="text" name="recommended_memory" class="form-control mb-2" placeholder="Memoria" value="{{ old('recommended_memory') }}" required>
                        <input type="text" name="recommended_graphics" class="form-control mb-2" placeholder="Gráficos" value="{{ old('recommended_graphics') }}" required>
                        <input type="text" name="recommended_storage" class="form-control" placeholder="Almacenamiento" value="{{ old('recommended_storage') }}" required>
                        <input type="text" name="recommended_directx" class="form-control mb-2" placeholder="DirectX" value="{{ old('recommended_directx') }}" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Botones -->
        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn btn-steam">
                Crear Juego
            </button>
            <a href="{{ route('admin.games.index') }}" class="btn btn-outline-light">Cancelar</a>
        </div>
    </form>
</div>

<!-- JavaScript para añadir/quitar inputs -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        function setupDynamic(wrapperSelector, addBtnSelector, removeClass) {
            const wrapper = document.querySelector(wrapperSelector);
            document.querySelector(addBtnSelector)
                .addEventListener('click', () => {
                    const group = document.createElement('div');
                    group.className = 'input-group mb-2';
                    group.innerHTML = `
                        <input type="text" name="${ wrapperSelector.includes('feature') ? 'features' : 'tags' }[]" class="form-control" placeholder="Nuevo" required>
                        <button class="btn btn-outline-danger ${removeClass}" type="button">&times;</button>
                    `;
                    wrapper.append(group);
                });
            wrapper.addEventListener('click', e => {
                if (e.target.classList.contains(removeClass)) {
                    e.target.closest('.input-group').remove();
                }
            });
        }

        setupDynamic('#features-wrapper', '#add-feature', 'remove-feature');
        setupDynamic('#tags-wrapper', '#add-tag', 'remove-tag');
    });
</script>


@endsection