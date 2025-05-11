@extends('adminlte::page')

@section('title', 'Crear Recomendación')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="h3">Crear Recomendación</h1>
    <a href="{{ route('admin.recomendations.index') }}" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@stop

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">

            <div class="card shadow">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">Nueva Recomendación</h5>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.recomendations.store') }}" method="POST" novalidate>
                        @csrf

                        <div class="form-floating mb-4">
                            <input
                                type="text"
                                name="titulo"
                                id="titulo"
                                class="form-control @error('titulo') is-invalid @enderror"
                                placeholder="Título"
                                value="{{ old('titulo') }}"
                                autofocus>
                            <label for="titulo">Título</label>
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-floating mb-4">
                            <textarea
                                name="descripcion"
                                id="descripcion"
                                class="form-control @error('descripcion') is-invalid @enderror"
                                placeholder="Descripción"
                                style="height: 150px;">{{ old('descripcion') }}</textarea>
                            <label for="descripcion">Descripción</label>
                            @error('descripcion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit"
                                    class="btn btn-lg btn-success">
                                <i class="fas fa-save me-1"></i> Guardar
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>
@stop
