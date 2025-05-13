{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>{{ config('app.name','SinPicos') }}</title>

  {{-- Bootstrap CSS --}}
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  {{-- DataTables CSS (dentro de head) --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

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
    .navbar .logo-text { font-family:'Pacifico',cursive; font-size:1.5rem; color:#7c3aed; }
    .navbar .logo-img { height:40px; width:auto; }
    .navbar { box-shadow:0 2px 4px rgba(0,0,0,0.05); padding:.5rem 0; }
    .navbar .menu a { margin-right:1rem; color:#333; font-weight:500; }
    .navbar .menu a:hover { color:#7c3aed; }
    .login-link {
      color:#7c3aed; font-weight:600; text-decoration:none;
      display:inline-flex; align-items:center; gap:.25rem;
      transition:color .2s;
    }
    .login-link:hover { color:#5e2ec6; text-decoration:underline; }
    .initials {
      background:#7c3aed; color:#fff; border-radius:50%;
      width:32px; height:32px; display:flex;
      align-items:center; justify-content:center;
      font-weight:bold; margin-right:.5rem;
    }
    /* Ocultar el caret azul de dropdown */
    .dropdown-toggle::after { display:none !important; }
  </style>
</head>
<body>

  {{-- NAVBAR --}}
  <nav class="navbar bg-white">
    <div class="container d-flex align-items-center justify-content-between">
      {{-- Logo --}}
      <a href="{{ route('home') }}" class="d-flex align-items-center text-decoration-none">
        <img src="{{ asset('images/Logo.png') }}" alt="SinPicos logo" class="logo-img me-2">
        <h1 class="logo-text mb-0">SinPicos</h1>
      </a>
      {{-- Menú --}}
      <div class="menu d-flex align-items-center">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('meals.create') }}">Registro de Comidas</a>
        <a href="{{ route('statistics') }}">Estadísticas</a>
        <a href="#">Sugerencias</a>
      </div>
      {{-- Usuario / Entrar --}}
      <div>
        @auth
          <div class="dropdown">
            <a class="d-flex align-items-center text-decoration-none dropdown-toggle"
               href="#" id="userMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
              <div class="initials">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
              <span class="text-dark">{{ auth()->user()->name }}</span>
              <i class="ri-arrow-down-s-line" style="color:#7c3aed;font-size:1.25rem;margin-left:.5rem;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenuLink">
              <li>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button class="dropdown-item" type="submit">Cerrar sesión</button>
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('login') }}" class="login-link">
            <i class="ri-user-line fs-5"></i> Entrar
          </a>
        @endauth
      </div>
    </div>
  </nav>

  {{-- CONTENIDO PRINCIPAL --}}
  <div class="main container" style="padding:4rem 0;">
    @yield('content')
  </div>

  {{-- jQuery + DataTables JS (antes de stack scripts) --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  @stack('scripts')
</body>
</html>
