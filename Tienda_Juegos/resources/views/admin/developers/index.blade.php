@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2>Desarrolladores</h2>
        <a href="{{ route('admin.developers.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo
        </a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr><th>Logo</th><th>Nombre</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            @foreach($developers as $dev)
            <tr>
                <td>
                    @if($dev->logo)
                    <img src="{{ asset('storage/'.$dev->logo) }}" alt="" style="height:40px">
                    @endif
                </td>
                <td>{{ $dev->name }}</td>
                <td>
                    <a href="{{ route('admin.developers.edit',$dev) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form method="POST" action="{{ route('admin.developers.destroy',$dev) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $developers->links() }}
</div>
@endsection
