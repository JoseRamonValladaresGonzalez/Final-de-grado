@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center my-4">
        <h2>Editores</h2>
        <a href="{{ route('admin.publishers.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo
        </a>
    </div>

    <table class="table table-striped">
        <thead>
            <tr><th>Logo</th><th>Nombre</th><th>Acciones</th></tr>
        </thead>
        <tbody>
            @foreach($publishers as $pub)
            <tr>
                <td>
                    @if($pub->logo)
                    <img src="{{ asset('storage/'.$pub->logo) }}" alt="" style="height:40px">
                    @endif
                </td>
                <td>{{ $pub->name }}</td>
                <td>
                    <a href="{{ route('admin.publishers.edit',$pub) }}" class="btn btn-sm btn-primary">Editar</a>
                    <form method="POST" action="{{ route('admin.publishers.destroy',$pub) }}" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $publishers->links() }}
</div>
@endsection
