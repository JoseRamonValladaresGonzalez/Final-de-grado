@extends('layouts.app')

@section('content')
<div class="container my-5 text-white">
  <div class="row justify-content-center">
    <div class="col-md-6">
     <div class="card" style="background-color: var(--steam-blue); border: none;">
      <div class="card-body text-white">
          <h3 class="text-steam-accent mb-4">Iniciar Sesión</h3>

          <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email -->
            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input id="email"
                     type="email"
                     name="email"
                     value="{{ old('email') }}"
                     class="form-control bg-light text-dark @error('email') is-invalid @enderror"
                     required autofocus>
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <!-- Contraseña -->
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

            <!-- Recuérdame -->
            <div class="form-check mb-4">
              <input id="remember_me"
                     type="checkbox"
                     name="remember"
                     class="form-check-input">
              <label for="remember_me" class="form-check-label">
                Recuérdame
              </label>
            </div>

            <div class="d-flex justify-content-between align-items-center">
              @if (Route::has('password.request'))
                <a class="text-steam-accent" href="{{ route('password.request') }}">
                  ¿Olvidaste tu contraseña?
                </a>
              @endif

              <button type="submit" class="btn-steam">
                Entrar
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection
