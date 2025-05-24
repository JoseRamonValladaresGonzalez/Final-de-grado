@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="my-4">Editar Editor</h2>
    <form method="POST" action="{{ route('admin.publishers.update', $publisher) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="mb-3">
            <label>Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name',$publisher->name) }}" required>
        </div>
        <div class="mb-3">
            <label>Descripción</label>
            <textarea name="description" class="form-control">{{ old('description',$publisher->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label>Logo</label>
            <input type="file" name="logo" class="form-control">
            @if($publisher->logo)
            <img src="{{ asset('storage/'.$publisher->logo) }}" style="height:60px" class="mt-2">
            @endif
        </div>
        <button class="btn btn-steam">Actualizar</button>
    </form>
</div>
@endsection
