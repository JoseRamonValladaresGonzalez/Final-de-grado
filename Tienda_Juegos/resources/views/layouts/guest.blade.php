<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <!-- …tus meta tags, csrf, etc… -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/steam.css') }}">
</head>
<body class="font-sans antialiased bg-steam-dark">

  <nav class="navbar navbar-expand-lg navbar-steam">
    <div class="container-fluid px-4">
      {{-- En lugar del logo de Laravel: pon tu imagen circular --}}
      <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{ asset('images/logo.png') }}"
             alt="Logo"
             style="height:40px;width:40px;object-fit:cover;border-radius:50%;">
      </a>
      <!-- aquí tu carrito y login/register tal cual lo definimos antes… -->
    </div>
  </nav>

  <main class="py-5">
    @yield('content')
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
