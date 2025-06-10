@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4 text-steam-accent">Gestión de Desarrolladores</h1>
        <a href="{{ route('admin.developers.create') }}" class="btn-steam">
            <i class="fas fa-plus me-1"></i> Nuevo Desarrollador
        </a>
    </div>

    <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-dark align-middle">
                    <thead>
                        <tr class="text-white">
                            <th>Logo</th>
                            <th>Nombre</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($developers as $dev)
                        <tr class="text-white">
                            <td style="width: 100px;">
                                @if($dev->logo)
                                    <img src="{{ asset('storage/'.$dev->logo) }}"
                                         alt="{{ $dev->name }}"
                                         class="img-fluid rounded"
                                         style="width: 80px; height: 45px; object-fit: cover;">
                                @endif
                            </td>
                            <td>{{ $dev->name }}</td>
                            <td class="text-center" style="width: 150px;">
                                <a href="{{ route('admin.developers.edit', $dev) }}"
                                   class="btn-steam btn-sm me-1" title="Editar">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <form action="{{ route('admin.developers.destroy', $dev) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este desarrollador?');">
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
                            <td colspan="3" class="text-center text-white py-4">
                                No hay desarrolladores registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
   <div class="d-flex justify-content-center mt-1">
       {{ $developers->links('pagination::bootstrap-5') }}
    </div>
        </div>
    </div>
</div>
@endsection
