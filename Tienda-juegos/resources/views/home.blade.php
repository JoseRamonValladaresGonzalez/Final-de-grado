@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Últimos lanzamientos</h2>
    
    <div class="game-list">
        @foreach($games as $game)
        <div class="game-item mb-4 p-3">
            <div class="d-flex align-items-center">
                <!-- Imagen -->
                <div class="game-image-container">
                    <img src="{{ asset('storage/' . $game->main_image) }}" 
                         class="game-image" 
                         alt="{{ $game->title }}">
                </div>
                
                <!-- Contenido -->
                <div class="game-content flex-grow-1 ms-4">
                    <h3 class="game-title mb-1">
                        <a href="{{ route('game.show', $game->slug) }}" 
                           class="text-decoration-none text-white">
                            {{ $game->title }}
                        </a>
                    </h3>
                    
                    @if($game->genres->count())
                    <div class="genre-list mb-2">
                        @foreach($game->genres as $genre)
                        <span class="badge bg-steam-accent">{{ $genre->name }}</span>
                        @endforeach
                    </div>
                    @endif
                    
                    <p class="game-description text-muted">
                        {{ Str::limit($game->description, 200) }}
                    </p>
                </div>
                
                <!-- Precio -->
                <div class="game-pricing text-end ms-4">
                    @if($game->discount)
                    <div class="discount-badge">
                        -{{ $game->discount }}%
                    </div>
                    @endif
                    <div class="h4 text-success">
                        {{ number_format($game->price - ($game->price * $game->discount / 100), 2) }}€
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{ $games->links() }}
</div>
@endsection