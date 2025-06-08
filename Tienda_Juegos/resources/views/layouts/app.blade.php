<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Bootstrap 5 desde CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Tu CSS custom Steam -->
    <link rel="stylesheet" href="{{ asset('css/steam.css') }}">
</head>

<body class="font-sans antialiased">
    <nav class="navbar navbar-expand-lg navbar-steam">
        <div class="container-fluid px-4">
            <!-- Logo circular -->
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('storage/images/logo.png') }}"
                    alt="{{ config('app.name') }}"
                    style="height:40px; width:40px; object-fit:cover; border-radius:50%;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav" aria-controls="navbarNav"
                aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">

                    <!-- Buscador -->
                    <li class="nav-item me-2">
                        <form class="d-flex" method="GET" action="{{ route('home') }}">
                            <input class="form-control form-control-sm"
                                type="search" name="q"
                                value="{{ request('q') }}"
                                placeholder="Buscar..." aria-label="Buscar">
                            <button class="btn btn-sm btn-steam ms-1">Go</button>
                        </form>
                    </li>

                    <!-- Historial de búsquedas -->
                    @if(session('search_history'))
                    <li class="nav-item dropdown me-2">
                        <a class="nav-link dropdown-toggle" href="#" id="historyDropdown"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Últimas
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="historyDropdown">
                            @foreach(session('search_history') as $term)
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('home', array_merge(request()->except('page'), ['q' => $term])) }}">
                                    {{ $term }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>
                    @endif

                    <!-- Tienda -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Tienda</a>
                    </li>

                    <!-- Categorías dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="catDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categorías
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="catDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route('home') }}">
                                    Todas
                                </a>
                            </li>
                            @foreach($categories as $cat)
                            <li>
                                <a class="dropdown-item"
                                    href="{{ route('home.category', $cat->id) }}">
                                    {{ $cat->name }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </li>

                    <!-- Carrito -->
                    <li class="nav-item position-relative">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            Carrito
                            @auth
                            @php $count = auth()->user()->cart()->count(); @endphp
                            @if($count > 0)
                            <span class="badge bg-steam-accent rounded-circle position-absolute top-0 start-100 translate-middle">
                                {{ $count }}
                            </span>
                            @endif
                            @endauth
                        </a>
                    </li>

                    @guest
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Registro</a></li>
                    @else
                    <!-- Perfil / Cerrar sesión -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userMenu" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            {{ auth()->user()->name }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-1"></i> Mi Perfil
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="fas fa-history me-1"></i> Historial
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-sign-out-alt me-1"></i> Cerrar Sesión
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endguest

                </ul>
            </div>
        </div>
    </nav>

    <div class="min-h-screen">
        @isset($header)
        <header class="bg-white shadow">
            <div class="container-fluid px-4 py-4">
                {{ $header }}
            </div>
        </header>
        @endisset

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>