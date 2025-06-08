@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mt-4 text-steam-accent">Gestión de Usuarios</h1>
        <a href="{{ route('admin.users.create') }}" class="btn-steam">
            <i class="fas fa-plus me-1"></i> Nuevo Usuario
        </a>
    </div>

    <div class="card mb-4" style="background-color: var(--steam-blue); border: none;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-dark align-middle">
                    <thead>
                        <tr class="text-white">
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Creado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr class="text-white">
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="text-center" style="width: 140px;">
                                <a href="{{ route('admin.users.edit', $user) }}"
                                   class="btn-steam btn-sm me-1" title="Editar">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </a>
                                <form action="{{ route('admin.users.destroy', $user) }}"
                                      method="POST" class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este usuario?');">
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
                            <td colspan="4" class="text-center text-white">
                                No hay usuarios registrados.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            <div class="d-flex justify-content-center mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
