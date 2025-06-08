@extends('layouts.app')

@section('content')
<div class="container my-5 text-white">
    <h1>¡Pago exitoso!</h1>
    <p>Hemos enviado tus claves de Steam a <strong>{{ auth()->user()->email }}</strong>.</p>

    <a href="{{ route('home') }}" class="btn btn-steam mt-4">Volver al inicio</a>
</div>
@endsection
