@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-steam-dark d-flex justify-content-center align-items-start py-5">
  <div class="container" style="max-width: 400px;">
    <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
      <div class="card-body">
        {{-- Aquí forzamos el color blanco --}}
        <p class="login-box-msg text-white">Admin Login</p>

        <form action="{{ route('admin.login') }}" method="post">
          @csrf
          <div class="input-group mb-3">
  <input
    type="email"
    name="email"
    class="form-control bg-steam-blue text-black placeholder-gray-400 border-secondary"
    placeholder="Email"
    required>
  <span class="input-group-text bg-steam-blue border-secondary">
    <i class="fas fa-envelope text-white"></i>
  </span>
</div>
<div class="input-group mb-4">
  <input
    type="password"
    name="password"
    class="form-control bg-steam-blue text-black placeholder-gray-400 border-secondary"
    placeholder="Password"
    required>
  <span class="input-group-text bg-steam-blue border-secondary">
    <i class="fas fa-lock text-white"></i>
  </span>
</div>

          <button type="submit" class="btn-steam w-100">
            Sign In
          </button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
