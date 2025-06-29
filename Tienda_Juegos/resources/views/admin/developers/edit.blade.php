@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-steam-accent">Editar Desarrollador</h1>

    <div class="card mt-4 mb-5" style="background-color: var(--steam-blue); border: none;">
        <div class="card-body text-white">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.developers.update', $developer) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $developer->name) }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" class="form-control" rows="4">{{ old('description', $developer->description) }}</textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" class="form-control">
                    @if($developer->logo)
                        <div class="mt-2">
                            <img src="{{ asset('storage/'.$developer->logo) }}"
                                 alt="Logo actual"
                                 style="height:60px; border-radius: 4px;">
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn-steam">
                        <i class="fas fa-save me-1"></i> Actualizar
                    </button>
                    <a href="{{ route('admin.developers.index') }}" class="btn btn-outline-light">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
