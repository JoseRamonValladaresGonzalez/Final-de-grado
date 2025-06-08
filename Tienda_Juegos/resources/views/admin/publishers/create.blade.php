@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="card mt-4 mb-4" style="background-color: var(--steam-blue); border: none;">
        <div class="card-body">
            <h2 class="mb-4 text-steam-accent">Nuevo Editor</h2>
            <form method="POST" action="{{ route('admin.publishers.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label text-white">Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Descripción</label>
                    <textarea name="description" class="form-control">{{ old('description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label text-white">Logo</label>
                    <input type="file" name="logo" class="form-control">
                </div>

                <button type="submit" class="btn-steam mt-3">
                    <i class="fas fa-plus me-1"></i> Crear
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
