{{-- resources/views/admin/ingredients/show.blade.php --}}
@extends('adminlte::page')

@section('title', 'Ver Ingrediente')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 font-weight-bold">Ingrediente #{{ $ingredient->id }}</h1>
    <div>
        <a href="{{ route('admin.ingredients.index') }}" 
           class="btn btn-outline-secondary rounded-pill me-2">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="{{ route('admin.ingredients.edit', $ingredient) }}" 
           class="btn btn-warning rounded-pill">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title text-white">Detalles del Ingrediente</h3>
            </div>
            <div class="card-body">
                <dl class="row mb-3">
                    <dt class="col-sm-5 text-muted">Nombre</dt>
                    <dd class="col-sm-7">{{ $ingredient->name }}</dd>

                    <dt class="col-sm-5 text-muted">Carbohidratos (g)</dt>
                    <dd class="col-sm-7">{{ $ingredient->carbohydrates }}</dd>

                    <dt class="col-sm-5 text-muted">Proteínas (g)</dt>
                    <dd class="col-sm-7">{{ $ingredient->proteins }}</dd>

                    <dt class="col-sm-5 text-muted">Grasas (g)</dt>
                    <dd class="col-sm-7">{{ $ingredient->fats }}</dd>

                    <dt class="col-sm-5 text-muted">Calorías</dt>
                    <dd class="col-sm-7">{{ $ingredient->calories }}</dd>
                </dl>
                <p class="text-sm text-muted">
                    Creado: {{ $ingredient->created_at->format('d/m/Y H:i') }}<br>
                    Actualizado: {{ $ingredient->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@stop
