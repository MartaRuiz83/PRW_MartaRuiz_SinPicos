{{-- resources/views/admin/users/create.blade.php --}}
@extends('adminlte::page')

@section('title', 'Crear Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title text-white">Nuevo Usuario</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    {{-- Nombre --}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-primary text-white">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <input
                            type="text"
                            name="name"
                            class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nombre"
                            value="{{ old('name') }}"
                            required
                        >
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Email --}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-info text-white">
                                <i class="fas fa-envelope"></i>
                            </span>
                        </div>
                        <input
                            type="email"
                            name="email"
                            class="form-control @error('email') is-invalid @enderror"
                            placeholder="Email"
                            value="{{ old('email') }}"
                            required
                        >
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Rol (select con dos opciones) --}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-success text-white">
                                <i class="fas fa-user-cog"></i>
                            </span>
                        </div>
                        <select
                            name="rol"
                            class="form-control @error('rol') is-invalid @enderror"
                            required
                        >
                            <option value="" disabled {{ old('rol') ? '' : 'selected' }}>Selecciona un rol</option>
                            <option value="Usuario" {{ old('rol')=='Usuario' ? 'selected' : '' }}>Usuario</option>
                            <option value="Administrador" {{ old('rol')=='Administrador' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Tipo de diabetes --}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-warning text-white">
                                <i class="fas fa-heartbeat"></i>
                            </span>
                        </div>
                        <select
                            name="tipo_diabetes"
                            class="form-control @error('tipo_diabetes') is-invalid @enderror"
                            required
                        >
                            <option value="" disabled {{ old('tipo_diabetes') ? '' : 'selected' }}>Selecciona tipo de diabetes</option>
                            <option value="Tipo 1" {{ old('tipo_diabetes')=='Tipo 1'?'selected':'' }}>Tipo 1</option>
                            <option value="Tipo 2" {{ old('tipo_diabetes')=='Tipo 2'?'selected':'' }}>Tipo 2</option>
                            <option value="Gestacional" {{ old('tipo_diabetes')=='Gestacional'?'selected':'' }}>Gestacional</option>
                        </select>
                        @error('tipo_diabetes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Contraseña --}}
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-danger text-white">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input
                            type="password"
                            name="password"
                            class="form-control @error('password') is-invalid @enderror"
                            placeholder="Contraseña"
                            required
                        >
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Confirmar contraseña --}}
                    <div class="input-group mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-secondary text-white">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            placeholder="Confirmar Contraseña"
                            required
                        >
                    </div>

                    {{-- Botones --}}
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
