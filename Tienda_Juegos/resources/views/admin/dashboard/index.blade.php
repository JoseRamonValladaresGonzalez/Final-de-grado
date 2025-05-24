@extends('layouts.app')

@section('content')
<div class="card bg-primary text-white mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5>Juegos</h5>
                <h2>{{ $gameCount }}</h2> <!-- Usar la variable pasada -->
            </div>
            <i class="fas fa-gamepad fa-3x"></i>
        </div>
    </div>
    <div class="card-footer d-flex align-items-center justify-content-between">
        <a class="small text-white stretched-link" href="{{ route('admin.games.index') }}">
            Gestionar Juegos
        </a>
        <div class="small text-white"><i class="fas fa-arrow-right"></i></div>
    </div>
</div>
@endsection