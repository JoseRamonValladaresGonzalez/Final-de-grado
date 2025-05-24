@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4">Nuevo Editor</h2>
    <form method="POST" action="{{ route('admin.publishers.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control">
        </div>
        <button class="btn btn-steam">Crear</button>
    </form>
</div>
@endsection
