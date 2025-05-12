@extends('layouts.app')

@section('content')
<div class="game-header" 
     style="background-image: linear-gradient(180deg, rgba(23,26,33,0) 50%, #0e1318 100%), 
            url({{ asset('storage/' . $game->header_image) }});">
</div>

<div class="game-container container py-5">
    <div class="row">
        <!-- Columna principal -->
        <div class="col-lg-8">
            <h1 class="display-4 mb-4">{{ $game->title }}</h1>
            
            <!-- Galería -->
            <div class="screenshot-grid mb-5">
                @foreach($game->screenshots as $screenshot)
                <img src="{{ asset('storage/' . $screenshot->path) }}" 
                     class="img-fluid rounded-3" 
                     alt="Captura de pantalla">
                @endforeach
            </div>
            
            <!-- Descripción -->
            <div class="mb-5">
                <h3 class="mb-3">Acerca de este juego</h3>
                <p class="lead">{{ $game->description }}</p>
            </div>
            
            <!-- Características -->
            @if($game->features)
            <div class="row mb-5">
                <h3 class="mb-4">Características principales</h3>
                @foreach(json_decode($game->features) as $feature)
                <div class="col-md-6 mb-3">
                    <div class="d-flex align-items-center">
                        <span class="text-success me-2">✓</span>
                        {{ $feature }}
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 20px;">
                <!-- Compra -->
                <div class="buy-section mb-4">
                    <div class="price-tag text-center mb-3">
                        @if($game->discount)
                        <div class="text-decoration-line-through small">
                            {{ number_format($game->price, 2) }}€
                        </div>
                        <div class="h2 text-success">
                            {{ number_format($game->price - ($game->price * $game->discount / 100), 2) }}€
                        </div>
                        <div class="badge bg-warning text-dark mt-2">
                            -{{ $game->discount }}%
                        </div>
                        @else
                        <div class="h2 text-success">
                            {{ number_format($game->price, 2) }}€
                        </div>
                        @endif
                    </div>
                    <button class="btn btn-steam w-100 py-3">
                        Añadir al carrito
                    </button>
                </div>
                
                <!-- Requisitos -->
                @if($game->requirements)
                <div class="system-requirements mb-4">
                    <h4 class="mb-3">Requisitos del sistema</h4>
                    @foreach(json_decode($game->requirements) as $key => $value)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">{{ $key }}:</span>
                        <span>{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
                @endif
                
                <!-- Metadata -->
                <div class="game-info">
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Desarrollador:</span>
                        <span>{{ $game->developer }}</span>
                    </div>
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted">Fecha de lanzamiento:</span>
                        <span>{{ $game->release_date->format('d M Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection