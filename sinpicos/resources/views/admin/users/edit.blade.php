{{-- resources/views/admin/users/edit.blade.php --}}
@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title text-white">Editar Usuario #{{ $user->id }}</h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                            value="{{ old('name', $user->name) }}"
                            required
                        >
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

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
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-success text-white">
                                <i class="fas fa-user-cog"></i>
                            </span>
                        </div>
                        <input
                            type="text"
                            name="rol"
                            class="form-control @error('rol') is-invalid @enderror"
                            placeholder="Rol"
                            value="{{ old('rol', $user->rol) }}"
                            required
                        >
                        @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-warning text-white">
                                <i class="fas fa-heartbeat"></i>
                            </span>
                        </div>
                        <input
                            type="text"
                            name="tipo_diabetes"
                            class="form-control @error('tipo_diabetes') is-invalid @enderror"
                            placeholder="Tipo de Diabetes"
                            value="{{ old('tipo_diabetes', $user->tipo_diabetes) }}"
                            required
                        >
                        @error('tipo_diabetes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

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
                            placeholder="Contraseña (dejar en blanco para no cambiar)"
                        >
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

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
                        >
                    </div>

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
