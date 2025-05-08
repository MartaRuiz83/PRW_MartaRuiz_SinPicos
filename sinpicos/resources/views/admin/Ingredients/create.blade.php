{{-- resources/views/admin/ingredients/create.blade.php --}}
@extends('adminlte::page')

@section('title', 'Nuevo Ingrediente')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-dark font-weight-bold">Nuevo Ingrediente</h1>
    <a href="{{ route('admin.ingredients.index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Volver
    </a>
</div>
@stop

@section('content')
    @if(session('success'))
        <x-adminlte-alert theme="success" title="¡Éxito!">
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.ingredients.store') }}" method="POST">
                @csrf

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-primary text-white">
                            <i class="fas fa-tag"></i>
                        </span>
                    </div>
                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nombre del ingrediente"
                        value="{{ old('name') }}"
                        required
                    >
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-info text-white">
                            <i class="fas fa-bread-slice"></i>
                        </span>
                    </div>
                    <input
                        type="number"
                        name="carbohydrates"
                        step="0.01"
                        class="form-control @error('carbohydrates') is-invalid @enderror"
                        placeholder="Carbohidratos (g)"
                        value="{{ old('carbohydrates') }}"
                    >
                    @error('carbohydrates')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-success text-white">
                            <i class="fas fa-drumstick-bite"></i>
                        </span>
                    </div>
                    <input
                        type="number"
                        name="proteins"
                        step="0.01"
                        class="form-control @error('proteins') is-invalid @enderror"
                        placeholder="Proteínas (g)"
                        value="{{ old('proteins') }}"
                    >
                    @error('proteins')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-warning text-white">
                            <i class="fas fa-oil-can"></i>
                        </span>
                    </div>
                    <input
                        type="number"
                        name="fats"
                        step="0.01"
                        class="form-control @error('fats') is-invalid @enderror"
                        placeholder="Grasas (g)"
                        value="{{ old('fats') }}"
                    >
                    @error('fats')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-danger text-white">
                            <i class="fas fa-fire"></i>
                        </span>
                    </div>
                    <input
                        type="number"
                        name="calories"
                        class="form-control @error('calories') is-invalid @enderror"
                        placeholder="Calorías"
                        value="{{ old('calories') }}"
                    >
                    @error('calories')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                    <a href="{{ route('admin.ingredients.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>
@stop
