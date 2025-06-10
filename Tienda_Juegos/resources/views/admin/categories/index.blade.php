@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4 text-steam-accent">Categorías</h1>
        <a href="{{ route('admin.categories.create') }}" class="btn-steam">
            <i class="fas fa-plus me-1"></i> Nueva Categoría
        </a>
    </div>

    <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-dark table-striped align-middle">
                    <thead>
                        <tr class="text-white">
                            <th>Nombre</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr class="text-white">
                            <td>{{ $cat->name }}</td>
                            <td class="text-center" style="width: 150px;">
                                <a href="{{ route('admin.categories.edit', $cat) }}"
                                   class="btn-steam btn-sm me-1">
                                   <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <form action="{{ route('admin.categories.destroy', $cat) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar esta categoría?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-light btn-sm">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center text-white">
                                No hay categorías registradas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

    <!-- Paginación -->
    <div class="d-flex justify-content-center mt-1">
       {{ $categories->links('pagination::bootstrap-5') }}
    </div>
        </div>
    </div>
</div>
@endsection
