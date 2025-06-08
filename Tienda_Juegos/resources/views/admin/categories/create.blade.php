@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-steam-accent">Nueva Categoría</h1>

    <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-white">Nombre</label>
                    <input type="text" name="name"
                           class="form-control bg-light text-dark @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn-steam">Crear</button>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-light">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
