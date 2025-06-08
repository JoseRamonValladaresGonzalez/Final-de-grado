@extends('layouts.admin')

@section('content')
<div class="cart-summary mb-4 text-white">
    <div class="d-flex gap-3 justify-content-center">
        <a href="{{ route('admin.games.index') }}" class="btn-steam">
            <i class="fas fa-cogs me-1"></i> Gestionar Juegos
        </a>
        <a href="{{ route('admin.developers.index') }}" class="btn-steam">
            <i class="fas fa-cogs me-1"></i> Gestionar Desarrolladores
        </a>
        <a href="{{ route('admin.publishers.index') }}" class="btn-steam">
            <i class="fas fa-cogs me-1"></i> Gestionar Publishers
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn-steam">
            <i class="fas fa-cogs me-1"></i> Gestionar Usuarios
        </a>
    </div>
</div>
@endsection