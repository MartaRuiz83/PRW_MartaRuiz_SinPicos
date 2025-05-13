{{-- resources/views/welcome.blade.php --}}
@extends('layouts.app')

@section('content')
<style>
  .hero {
    background: #fff;
    padding: 6rem 0 2rem;
    position: relative;
  }
  .hero h1 span { color: #7d3ced; }
  .hero img.logo { max-width: 140px; margin-bottom: 1rem; }
  .hero p.lead { color: #555; max-width: 600px; margin: auto; }

  /* Botones más modernos */
  .btn-gradient {
    background: linear-gradient(135deg, #7d3ced 0%, #c77dff 100%);
    border: none;
    color: #fff;
    padding: 0.75rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 50px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    transition: transform .2s, box-shadow .2s;
  }
  .btn-gradient:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 25px rgba(0,0,0,0.2);
  }
  .btn-outline-gradient {
    background: transparent;
    border: 2px solid transparent;
    border-image: linear-gradient(135deg, #7d3ced, #c77dff) 1;
    color: #7d3ced;
    padding: 0.75rem 2rem;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 50px;
    transition: background .2s, color .2s, transform .2s;
  }
  .btn-outline-gradient:hover {
    background: linear-gradient(135deg, #7d3ced, #c77dff);
    color: #fff;
    transform: translateY(-3px);
  }
</style>

{{-- Hero --}}
<div class="hero text-center">
  <img src="{{ asset('images/logo.png') }}" alt="SinPicos Logo" class="logo">
  <h1 class="display-4 fw-bold">Bienvenido a <span>SinPicos</span></h1>
  <p class="lead mb-4">
    Lleva el control de tus comidas y macronutrientes sin picos de glucosa.
  </p>
  <a href="{{ route('login') }}" class="btn-gradient me-2">
    <i class="ri-login-circle-line fs-5 align-middle me-1"></i> Entrar
  </a>
  <a href="{{ route('register') }}" class="btn-outline-gradient">
    <i class="ri-user-add-line fs-5 align-middle me-1"></i> Regístrate
  </a>
</div>

{{-- Ondas y demás secciones no cambian... --}}

<div class="container py-5">
  <h2 class="text-center fw-bold mb-4" style="color: #7d3ced;">Características Destacadas</h2>
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card feature-card h-100 border-0 shadow-sm text-center p-4">
        <i class="ri-bar-chart-box-line fs-2 mb-3" style="color: #7d3ced;"></i>
        <h5 class="fw-bold mb-2">Gráficos Dinámicos</h5>
        <p class="text-muted">Visualiza tu ingesta diaria con charts interactivos y claros.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card feature-card h-100 border-0 shadow-sm text-center p-4">
        <i class="ri-apple-line fs-2 mb-3" style="color: #c77dff;"></i>
        <h5 class="fw-bold mb-2">Registro Rápido</h5>
        <p class="text-muted">Agrega alimentos en un par de clics y controla tus porciones.</p>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card feature-card h-100 border-0 shadow-sm text-center p-4">
        <i class="ri-fire-line fs-2 mb-3" style="color: #ff6b6b;"></i>
        <h5 class="fw-bold mb-2">Control Calórico</h5>
        <p class="text-muted">Suma automáticamente calorías y ajusta tu plan diario.</p>
      </div>
    </div>
  </div>
</div>

<div class="bg-light py-5">
  <div class="container text-center">
    <h3 class="fw-bold mb-3">Únete a SinPicos hoy</h3>
    <p class="mb-4 text-muted">Empieza gratis y mejora tu salud con datos reales.</p>
    <a href="{{ route('register') }}" class="btn-gradient">
      <i class="ri-user-add-line fs-5 align-middle me-1"></i> Regístrate
    </a>
  </div>
</div>
@endsection
