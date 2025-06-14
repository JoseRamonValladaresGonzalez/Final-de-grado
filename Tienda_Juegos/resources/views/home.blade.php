@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <!-- Carrusel de destacados -->
    <div id="mainCarousel" class="carousel slide mb-5 steam-carousel" data-bs-ride="carousel">
        <div class="carousel-inner">
            @foreach($featuredGames as $key => $game)
            <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                <img src="{{ asset('storage/'.$game->main_image) }}"
                     class="d-block w-100"
                     alt="{{ $game->title }}">
                <div class="carousel-caption steam-carousel-caption d-none d-md-block">
                    <h5>{{ $game->title }}</h5>
                    <p>Hasta {{ $game->discount_percent }}% de descuento</p>
                    <a href="{{ route('games.show', $game) }}" class="btn btn-steam">
                        <i class="fas fa-info-circle me-2"></i>Ver Detalles
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#mainCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#mainCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>

    <!-- Todos los Juegos con paginación -->
    <h2 class="text-steam-accent mb-4">Todos los Juegos</h2>

    <div class="game-list">
        @forelse($games as $game)
        <div class="game-item cart-item mb-4 d-flex align-items-start">
            <!-- Imagen -->
            <div class="game-image-container">
                <img src="{{ asset('storage/'.$game->main_image) }}"
                     class="game-image"
                     alt="{{ $game->title }}">
            </div>

            <!-- Título y descripción -->
            <div class="game-content flex-grow-1 ms-4">
                <h3 class="game-title mb-1">{{ $game->title }}</h3>
                <p class="game-description mb-0">
                    {{ Str::limit($game->description, 100) }}
                </p>
            </div>

            <!-- Precio + botón -->
            <div class="game-pricing text-end ms-4 d-flex flex-column justify-content-between">
                <div>
                    @if($game->discount_percent > 0)
                    <div class="discount-badge">-{{ $game->discount_percent }}%</div>
                    <div class="original-price text-muted text-decoration-line-through">
                        ${{ number_format($game->original_price, 2) }}
                    </div>
                    @endif
                    <div class="final-price h4">${{ number_format($game->current_price, 2) }}</div>
                </div>
                <a href="{{ route('games.show', $game) }}" class="btn btn-steam">
                    Añadir al carrito
                </a>
            </div>
        </div>
        @empty
        <p class="text-white">No hay juegos para mostrar.</p>
        @endforelse
    </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-1">
       {{ $games->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
