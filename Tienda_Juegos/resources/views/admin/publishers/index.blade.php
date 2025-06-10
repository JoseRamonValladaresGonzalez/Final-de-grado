@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4 text-steam-accent">Gestión de Editores</h1>
        <a href="{{ route('admin.publishers.create') }}" class="btn-steam">
            <i class="fas fa-plus me-1"></i> Nuevo Editor
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
                        @forelse($publishers as $pub)
                        <tr class="text-white">
                            <td style="width: 100px;">
                                @if($pub->logo)
                                <img src="{{ asset('storage/'.$pub->logo) }}"
                                     alt="{{ $pub->name }}"
                                     class="img-fluid rounded"
                                     style="width: 80px; height: 45px; object-fit: contain;">
                                @endif
                            </td>
                            <td>{{ $pub->name }}</td>
                            <td class="text-center" style="width: 120px;">
                                <a href="{{ route('admin.publishers.edit', $pub) }}"
                                   class="btn-steam btn-sm me-1" title="Editar">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <form action="{{ route('admin.publishers.destroy', $pub) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este editor permanentemente?');">
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
                            <td colspan="3" class="text-center text-white">No hay editores registrados.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-1">
       {{ $publishers->links('pagination::bootstrap-5') }}
    </div>
        </div>
    </div>
</div>
@endsection
