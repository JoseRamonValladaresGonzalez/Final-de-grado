<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Steam Style')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Estilos personalizados -->
    <style>
        :root {
            --steam-dark: #171a21;
            --steam-blue: #1b2838;
            --steam-accent: #66c0f4;
        }
        
        body {
            background-color: var(--steam-dark);
            color: #c6d4df;
        }
        
        /* Agrega aquí otros estilos comunes */
    </style>
</head>
<body>
    @include('partials.navbar') <!-- Si tienes una navbar -->
    
    <main class="container-fluid">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') <!-- Para scripts específicos de vistas -->
</body>
</html>