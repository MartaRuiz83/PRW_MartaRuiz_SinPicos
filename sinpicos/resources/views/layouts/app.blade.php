{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <title>{{ config('app.name','SinPicos') }}</title>

  {{-- Google Font Pacifico --}}
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  {{-- Remixicon --}}
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
  {{-- Tus estilos propios --}}
  <link href="{{ asset('css/style.css') }}" rel="stylesheet">
  {{-- ECharts --}}
  <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
</head>
<body>

  {{-- NAVBAR --}}
  <nav class="navbar">
    <div class="container inner">
      {{-- Logo --}}
      <a href="{{ route('home') }}" class="logo">SinPicos</a>

      {{-- Menú principal --}}
      <div class="menu flex items-center">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('meals.create') }}">Registro de Comidas</a>
        <a href="#">Estadísticas</a>
        <a href="#">Sugerencias</a>
      </div>

      {{-- Bloque de usuario --}}
      <div class="flex items-center space-x-4">
        @auth
          <div class="user-menu">
            <div class="user-menu-button">
              <div class="initials">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
              </div>
              <span class="username">{{ auth()->user()->name }}</span>
            </div>
            <div class="user-menu-dropdown">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
              </form>
            </div>
          </div>
        @else
          <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:underline">Entrar</a>
        @endauth
      </div>
    </div>
  </nav>

  {{-- CONTENIDO PRINCIPAL --}}
  <div class="main container" style="padding-top:4rem; padding-bottom:2rem;">
    @yield('content')
  </div>

  @stack('scripts')
</body>
</html>
