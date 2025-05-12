@extends('layouts.app')

@section('content')
<div class="container my-5">
    <!-- Cabecera -->
    <div class="game-header text-white mb-5">
        <h1 class="game-title">{{ $game->title }}</h1>
        <div class="row g-4 mt-4">
            <!-- Carousel Bootstrap -->
            <div class="col-md-8">
                <div id="screenshotsCarousel" class="carousel slide shadow-lg" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach($game->screenshots as $key => $screenshot)
                        <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                            <img
                                src="{{ asset('storage/screenshots/'.$screenshot->image_path) }}"
                                class="d-block w-100 game-screenshot"
                                alt="Captura {{ $key + 1 }}">
                        </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#screenshotsCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Anterior</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#screenshotsCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Siguiente</span>
                    </button>
                </div>
            </div>

            <!-- Sección de Compra -->
            <div class="col-md-4">
                <div class="buy-box">
                    @if($game->discount_percent > 0)
                    <div class="discount-badge mb-3">-{{ $game->discount_percent }}%</div>
                    @endif

                    <div class="d-flex align-items-baseline gap-2 mb-4">
                        @if($game->discount_percent > 0)
                        <div class="original-price">
                            ${{ number_format($game->original_price, 2) }}
                        </div>
                        @endif
                        <div class="current-price h2 text-steam-accent">
                            ${{ number_format($game->current_price, 2) }}
                        </div>
                    </div>

                    <form action="{{ route('cart.store', $game) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-steam btn-lg w-100 mb-3">
                            <i class="fas fa-cart-plus me-2"></i>Añadir al Carrito
                        </button>
                    </form>

                    <hr class="border-secondary">

                    <div class="game-info text-white">
                        <p><strong>Desarrollador:</strong> {{ $game->developer->name }}</p>
                        <p><strong>Editor:</strong> {{ $game->publisher->name }}</p>
                        <p><strong>Lanzamiento:</strong> {{ $game->release_date->format('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Descripción y características -->
    <div class="row mb-5">
        <div class="col-lg-8">
            <h3 class="text-steam-accent mb-3">Acerca del juego</h3>
            <p class="text-white">{{ $game->description }}</p>

            @if($game->features->isNotEmpty())
            <h4 class="text-steam-accent mt-4 mb-3">Características Principales</h4>
            <ul class="features-list text-white">
                @foreach($game->features as $feature)
                <li>{{ $feature->feature_text }}</li>
                @endforeach
            </ul>
            @endif

            @if($game->tags->isNotEmpty())
            <div class="tags mt-4">
                @foreach($game->tags as $tag)
                <span class="game-tag">{{ $tag->tag_name }}</span>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <!-- Requisitos del sistema -->
    @if($game->requirements->isNotEmpty())
    <div class="system-requirements mb-5">
        <div class="row g-4">
            @foreach(['minimum' => 'Mínimos', 'recommended' => 'Recomendados'] as $type => $title)
            @if($req = $game->requirements->where('requirement_type', $type)->first())
            <div class="col-md-6">
                <div class="req-block">
                    <h4 class="requirement-title">{{ $title }}</h4>
                    <dl class="row mt-3 text-white">
                        <dt class="col-sm-4">Sistema Operativo</dt>
                        <dd class="col-sm-8">{{ $req->os }}</dd>

                        <dt class="col-sm-4">Procesador</dt>
                        <dd class="col-sm-8">{{ $req->processor }}</dd>

                        <dt class="col-sm-4">Memoria</dt>
                        <dd class="col-sm-8">{{ $req->memory }}</dd>

                        <dt class="col-sm-4">Gráficos</dt>
                        <dd class="col-sm-8">{{ $req->graphics }}</dd>

                        <dt class="col-sm-4">Almacenamiento</dt>
                        <dd class="col-sm-8">{{ $req->storage }}</dd>
                    </dl>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection