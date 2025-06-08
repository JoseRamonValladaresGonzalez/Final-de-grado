@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4 text-steam-accent">Gestión de Juegos</h1>
        <a href="{{ route('admin.games.create') }}" class="btn-steam">
            <i class="fas fa-plus me-1"></i> Nuevo Juego
        </a>
    </div>

    <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-dark align-middle">
                    <thead>
                        <tr class="text-white">
                            <th>Imagen</th>
                            <th>Título</th>
                            <th>Precio</th>
                            <th>Descuento</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($games as $game)
                        <tr class="text-white">
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
                                    <span class="badge discount-badge">-{{ $game->discount_percent }}%</span>
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="text-center" style="width: 120px;">
                                <a href="{{ route('admin.games.edit', $game) }}"
                                   class="btn-steam btn-sm me-1" title="Editar">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <form action="{{ route('admin.games.destroy', $game) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este juego permanentemente?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-outline-light btn-sm"
                                            title="Eliminar">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-white">No hay juegos registrados.</td>
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
