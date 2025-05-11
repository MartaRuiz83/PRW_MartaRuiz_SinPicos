{{-- resources/views/admin/recomendations/show.blade.php --}}
@extends('adminlte::page')

@section('title', "Recomendación #{$recomendation->id}")

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="h3">
        <i class="fas fa-star text-warning"></i>
        Recomendación <span class="badge bg-secondary">#{{ $recomendation->id }}</span>
    </h1>
    <div>
        <a href="{{ route('admin.recomendations.index') }}" class="btn btn-outline-secondary me-1">
            <i class="fas fa-arrow-left"></i> Volver
        </a>
        <a href="{{ route('admin.recomendations.edit', $recomendation) }}" class="btn btn-outline-warning">
            <i class="fas fa-edit"></i> Editar
        </a>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid mt-4">
    <div class="row">
        {{-- Detalle principal --}}
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header bg-gradient-info text-white">
                    <h4 class="mb-0">{{ $recomendation->titulo }}</h4>
                </div>
                <div class="card-body">
                    <p class="lead">{{ $recomendation->descripcion }}</p>
                </div>
            </div>
        </div>

        {{-- Meta datos --}}
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header bg-gradient-dark text-white">
                    <h5 class="mb-0"><i class="fas fa-info-circle"></i> Detalles</h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>ID:</strong> <span class="badge bg-secondary">#{{ $recomendation->id }}</span>
                    </li>
                    <li class="list-group-item">
                        <strong>Usuario:</strong> {{ $recomendation->user->name ?? '—' }}
                    </li>
                    <li class="list-group-item">
                        <strong>Creado:</strong>
                        {{ $recomendation->created_at->format('d M Y H:i') }}
                        <br><small class="text-muted">({{ $recomendation->created_at->diffForHumans() }})</small>
                    </li>
                    <li class="list-group-item">
                        <strong>Actualizado:</strong>
                        {{ $recomendation->updated_at->format('d M Y H:i') }}
                        <br><small class="text-muted">({{ $recomendation->updated_at->diffForHumans() }})</small>
                    </li>
                </ul>
            </div>

            {{-- Acciones rápidas --}}
            <div class="card shadow">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-cogs"></i> Acciones</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('admin.recomendations.edit', $recomendation) }}"
                       class="btn btn-warning w-100 mb-2">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <form action="{{ route('admin.recomendations.destroy', $recomendation) }}"
                          method="POST"
                          onsubmit="return confirm('¿Seguro que deseas eliminar esta recomendación?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@stop
