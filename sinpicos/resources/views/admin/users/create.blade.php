@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content_header')
    <h1>Crear Usuario</h1>
@stop

@section('content')
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label for="name">Nombre</label>
            <input 
                type="text" 
                name="name" 
                id="name" 
                value="{{ old('name') }}" 
                class="form-control @error('name') is-invalid @enderror" 
                required
            >
            @error('name')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input 
                type="email" 
                name="email" 
                id="email" 
                value="{{ old('email') }}" 
                class="form-control @error('email') is-invalid @enderror" 
                required
            >
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="rol">Rol</label>
            <input 
                type="text" 
                name="rol" 
                id="rol" 
                value="{{ old('rol') }}" 
                class="form-control @error('rol') is-invalid @enderror" 
                required
            >
            @error('rol')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="tipo_diabetes">Tipo de Diabetes</label>
            <input 
                type="text" 
                name="tipo_diabetes" 
                id="tipo_diabetes" 
                value="{{ old('tipo_diabetes') }}" 
                class="form-control @error('tipo_diabetes') is-invalid @enderror" 
                required
            >
            @error('tipo_diabetes')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Contraseña</label>
            <input 
                type="password" 
                name="password" 
                id="password" 
                class="form-control @error('password') is-invalid @enderror" 
                required
            >
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input 
                type="password" 
                name="password_confirmation" 
                id="password_confirmation" 
                class="form-control" 
                required
            >
        </div>

        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-default">Cancelar</a>
    </form>
@stop
