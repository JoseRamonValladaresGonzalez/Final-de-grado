@extends('layouts.app')

@section('content')
<div class="container-fluid px-4 my-5">
    <h1 class="text-steam-accent mb-4">Mi Perfil</h1>

    <div class="row g-4">
        {{-- Información de usuario --}}
        <div class="col-12">
            <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                <div class="card-body">
                    <h5 class="text-white mb-3">Actualizar información</h5>
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3 row">
                            <label for="name" class="col-sm-3 col-form-label text-white">Nombre</label>
                            <div class="col-sm-9">
                                <input id="name" name="name" type="text"
                                       value="{{ old('name', auth()->user()->name) }}"
                                       class="form-control bg-light text-dark @error('name') is-invalid @enderror">
                                @error('name')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="email" class="col-sm-3 col-form-label text-white">Email</label>
                            <div class="col-sm-9">
                                <input id="email" name="email" type="email"
                                       value="{{ old('email', auth()->user()->email) }}"
                                       class="form-control bg-light text-dark @error('email') is-invalid @enderror">
                                @error('email')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="text-end">
                          <button type="submit" class="btn btn-steam">Guardar cambios</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Cambiar contraseña --}}
        <div class="col-12">
            <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                <div class="card-body">
                    <h5 class="text-white mb-3">Cambiar contraseña</h5>
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 row">
                            <label for="current_password" class="col-sm-4 col-form-label text-white">Actual</label>
                            <div class="col-sm-8">
                                <input id="current_password" name="current_password" type="password"
                                       class="form-control bg-light text-dark @error('current_password') is-invalid @enderror">
                                @error('current_password')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password" class="col-sm-4 col-form-label text-white">Nueva</label>
                            <div class="col-sm-8">
                                <input id="password" name="password" type="password"
                                       class="form-control bg-light text-dark @error('password') is-invalid @enderror">
                                @error('password')
                                  <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="password_confirmation" class="col-sm-4 col-form-label text-white">Confirmar</label>
                            <div class="col-sm-8">
                                <input id="password_confirmation" name="password_confirmation" type="password"
                                       class="form-control bg-light text-dark">
                            </div>
                        </div>

                        <div class="text-end">
                          <button type="submit" class="btn btn-steam">Cambiar contraseña</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Eliminar cuenta --}}
        <div class="col-12">
            <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
                <div class="card-body">
                    <h5 class="text-white mb-3">Eliminar cuenta</h5>
                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')
                        <p class="text-white">Al eliminar tu cuenta, se borrarán todos tus datos de forma permanente.</p>
                        <div class="text-end">
                          <button type="submit" class="btn btn-danger">Eliminar mi cuenta</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
