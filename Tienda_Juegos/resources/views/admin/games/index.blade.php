@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4">Gestión de Juegos</h1>
        <a href="{{ route('admin.games.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-1"></i> Nuevo Juego
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Precio</th>
                            <th>Descuento</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($games as $game)
                        <tr>
                            <td style="width: 100px;">
                                @if($game->main_image)
                                <img src="{{ asset('storage/'.$game->main_image) }}"
                                     alt="{{ $game->title }}"
                                     class="img-fluid rounded" 
                                     style="width: 80px; height: 45px; object-fit: cover;">
                                @endif
                            </td>
                            <td>{{ $game->title }}</td>
                            <td>€{{ number_format($game->current_price, 2) }}</td>
                            <td>
                                @if($game->discount_percent > 0)
                                    <span class="badge bg-steam-accent text-dark">
                                        -{{ $game->discount_percent }}%
                                    </span>
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="text-center" style="width: 120px;">
                                <a href="{{ route('admin.games.edit', $game) }}"
                                   class="btn btn-sm btn-primary me-1" title="Editar"> 
                                    <i class="fas fa-edit"> editar</i>
                                </a>
                                <form action="{{ route('admin.games.destroy', $game) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este juego permanentemente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger"
                                            title="Eliminar">eliminar
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No hay juegos registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $games->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
