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

  <style>
    /* Logo Pacifico */
    .navbar .logo-text {
      font-family: 'Pacifico', cursive;
      font-size: 1.5rem;
      color: #7c3aed;
      margin: 0;
    }
    .navbar .logo-img {
      height: 40px;
      width: auto;
    }
    .navbar {
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
      padding: .5rem 0;
    }
    .navbar .menu a {
      margin-right: 1rem;
      color: #333;
      font-weight: 500;
    }
    .navbar .menu a:hover {
      color: #7c3aed;
    }
    .user-menu-button .initials {
      background-color: #7c3aed;
      color: #fff;
      border-radius: 50%;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      margin-right: .5rem;
    }
    .user-menu-button {
      display: flex;
      align-items: center;
      cursor: pointer;
    }
    .user-menu-dropdown {
      display: none;
      position: absolute;
      background: #fff;
      box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
      border-radius: .25rem;
      margin-top: .5rem;
      right: 0;
      z-index: 1000;
    }
    .user-menu:hover .user-menu-dropdown {
      display: block;
    }
    .user-menu-dropdown button {
      background: none;
      border: none;
      padding: .5rem 1rem;
      width: 100%;
      text-align: left;
      color: #333;
    }
    .user-menu-dropdown button:hover {
      background-color: #f8f9fa;
    }

    /* Estilo para el enlace Entrar */
    .login-link {
      color: #7c3aed;
      font-weight: 600;
      text-decoration: none;
      transition: color .2s;
      display: inline-flex;
      align-items: center;
      gap: .25rem;
    }
    .login-link:hover {
      color: #5e2ec6;
      text-decoration: underline;
    }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  <nav class="navbar bg-white">
    <div class="container d-flex align-items-center justify-content-between">

      {{-- Logo --}}
      <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
        <img src="{{ asset('images/Logo.png') }}"
             alt="SinPicos logo"
             class="logo-img me-2">
        <h1 class="logo-text mb-0">SinPicos</h1>
      </a>

      {{-- Menú principal --}}
      <div class="menu d-flex align-items-center">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('meals.create') }}">Registro de Comidas</a>
        <a href="{{ route('statistics') }}">Estadísticas</a>
        <a href="#">Sugerencias</a>
      </div>

      {{-- Bloque de usuario --}}
      <div class="position-relative">
        @auth
          <div class="user-menu">
            <div class="user-menu-button">
              <div class="initials">
                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
              </div>
              <span class="username text-dark">{{ auth()->user()->name }}</span>
            </div>
            <div class="user-menu-dropdown">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
              </form>
            </div>
          </div>
        @else
          {{-- Enlace "Entrar" con icono de usuario --}}
          <a href="{{ route('login') }}" class="login-link">
            <i class="ri-user-line fs-5"></i> Entrar
          </a>
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
