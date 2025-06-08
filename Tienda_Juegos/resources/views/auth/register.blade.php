@extends('layouts.app')

@section('content')
<div class="container my-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card" style="background-color: var(--steam-blue); border:none;">
        <div class="card-body text-white"><!-- Aquí aplicamos el text-white -->
          <h3 class="text-steam-accent mb-4">Registro</h3>

          <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
              <label for="name" class="form-label">Nombre</label>
              <input id="name"
                     type="text"
                     name="name"
                     value="{{ old('name') }}"
                     class="form-control bg-light text-dark @error('name') is-invalid @enderror"
                     required autofocus>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input id="email"
                     type="email"
                     name="email"
                     value="{{ old('email') }}"
                     class="form-control bg-light text-dark @error('email') is-invalid @enderror"
                     required>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
              <label for="password" class="form-label">Contraseña</label>
              <input id="password"
                     type="password"
                     name="password"
                     class="form-control bg-light text-dark @error('password') is-invalid @enderror"
                     required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
              <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
              <input id="password_confirmation"
                     type="password"
                     name="password_confirmation"
                     class="form-control bg-light text-dark @error('password_confirmation') is-invalid @enderror"
                     required>
              @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center">
              <a class="text-steam-accent" href="{{ route('login') }}">
                ¿Ya estás registrado?
              </a>
              <button type="submit" class="btn-steam">
                Registrarse
              </button>
            </div>
          </form>
          
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
