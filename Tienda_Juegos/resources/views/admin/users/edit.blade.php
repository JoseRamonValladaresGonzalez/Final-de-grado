@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-steam-accent">Editar Usuario</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')
        <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label text-white">Nombre</label>
                    <input type="text" name="name" 
                           value="{{ old('name', $user->name) }}"
                           class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Email</label>
                    <input type="email" name="email" 
                           value="{{ old('email', $user->email) }}"
                           class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Nueva Contraseña <small>(opcional)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label text-white">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2">
            <button type="submit" class="btn-steam">Guardar Cambios</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-light">Cancelar</a>
        </div>
    </form>
</div>
@endsection
