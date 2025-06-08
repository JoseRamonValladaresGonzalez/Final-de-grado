@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="card mt-4 mb-4" style="background-color: var(--steam-blue); border: none;">
        <div class="card-body">
            <h2 class="mb-4 text-steam-accent">Editar Editor</h2>
            <form method="POST" action="{{ route('admin.publishers.update', $publisher) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label text-white">Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $publisher->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Descripción</label>
                    <textarea name="description" class="form-control">{{ old('description', $publisher->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Logo</label>
                    <input type="file" name="logo" class="form-control">
                    @if($publisher->logo)
                        <img src="{{ asset('storage/'.$publisher->logo) }}"
                             alt="Logo de {{ $publisher->name }}"
                             class="img-fluid mt-2 rounded"
                             style="height: 60px;">
                    @endif
                </div>

                <button type="submit" class="btn-steam mt-3">
                    <i class="fas fa-save me-1"></i> Actualizar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
