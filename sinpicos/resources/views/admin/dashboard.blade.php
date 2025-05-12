@extends('adminlte::page')
@section('title', 'Admin Dashboard')

@section('content_header')
  <h1>Admin Dashboard</h1>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row">

    <!-- Usuarios -->
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card text-white bg-primary h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
          <i class="fas fa-users fa-4x mb-3"></i>
          <h5 class="card-title">Usuarios</h5>
        </div>
        <a href="{{ route('admin.users.index') }}" class="card-footer text-center text-white py-2" style="background: rgba(0,0,0,0.1);">
          Gestionar &raquo;
        </a>
      </div>
    </div>

    <!-- Ingredientes -->
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card text-white bg-success h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
          <i class="fas fa-carrot fa-4x mb-3"></i>
          <h5 class="card-title">Ingredientes</h5>
        </div>
        <a href="{{ route('admin.ingredients.index') }}" class="card-footer text-center text-white py-2" style="background: rgba(0,0,0,0.1);">
          Gestionar &raquo;
        </a>
      </div>
    </div>

    <!-- Recomendaciones -->
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card text-white bg-warning h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
          <i class="fas fa-lightbulb fa-4x mb-3"></i>
          <h5 class="card-title">Recomendaciones</h5>
        </div>
        <a href="{{ route('admin.recomendations.index') }}" class="card-footer text-center text-white py-2" style="background: rgba(0,0,0,0.1);">
          Gestionar &raquo;
        </a>
      </div>
    </div>

    <!-- Home público -->
    <div class="col-lg-3 col-md-6 mb-4">
      <div class="card text-white bg-info h-100">
        <div class="card-body d-flex flex-column align-items-center justify-content-center">
          <i class="fas fa-home fa-4x mb-3"></i>
          <h5 class="card-title">Home</h5>
        </div>
        <a href="{{ route('home') }}" class="card-footer text-center text-white py-2" style="background: rgba(0,0,0,0.1);">
          Ver comidas &raquo;
        </a>
      </div>
    </div>

  </div>
</div>
@endsection

@push('css')
<style>
  /* Aumenta un poco la elevación al pasar por encima */
  .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.15);
    transition: all .2s ease-in-out;
  }
  /* Que el pie no “salte” de color */
  .card-footer {
    transition: background .2s ease-in-out;
  }
  .card-footer:hover {
    background: rgba(0,0,0,0.2) !important;
  }
</style>
@endpush
