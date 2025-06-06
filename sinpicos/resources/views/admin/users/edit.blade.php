@extends('adminlte::page')

@section('title', request()->routeIs('perfil.edit') ? 'Editar perfil' : 'Editar usuario')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-warning">
                <h3 class="card-title text-dark">
                    {{ request()->routeIs('perfil.edit') ? 'Editar mis datos' : 'Editar Usuario #' . $user->id }}
                </h3>
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ request()->routeIs('perfil.edit') ? route('perfil.update') : route('admin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

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
                            value="{{ old('name', $user->name) }}"
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
                            value="{{ old('email', $user->email) }}"
                            required
                        >
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Rol (solo visible para admins) --}}
                    @unless(request()->routeIs('perfil.edit'))
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
                            <option value="" disabled {{ old('rol', $user->rol) ? '' : 'selected' }}>Selecciona un rol</option>
                            <option value="Usuario"       {{ old('rol', $user->rol)=='Usuario'       ? 'selected' : '' }}>Usuario</option>
                            <option value="Administrador" {{ old('rol', $user->rol)=='Administrador' ? 'selected' : '' }}>Administrador</option>
                        </select>
                        @error('rol')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    @endunless

                    {{-- Tipo de Diabetes --}}
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
                            <option value="" disabled {{ old('tipo_diabetes', $user->tipo_diabetes) ? '' : 'selected' }}>Selecciona tipo de diabetes</option>
                            <option value="Tipo 1"       {{ old('tipo_diabetes', $user->tipo_diabetes)=='Tipo 1'       ? 'selected' : '' }}>Tipo 1</option>
                            <option value="Tipo 2"       {{ old('tipo_diabetes', $user->tipo_diabetes)=='Tipo 2'       ? 'selected' : '' }}>Tipo 2</option>
                            <option value="Gestacional" {{ old('tipo_diabetes', $user->tipo_diabetes)=='Gestacional' ? 'selected' : '' }}>Gestacional</option>
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
                            placeholder="Contraseña (dejar en blanco para no cambiar)"
                        >
                        @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    {{-- Confirmar Contraseña --}}
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

                    {{-- Botones --}}
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>

                        <a href="{{ request()->routeIs('perfil.edit') ? route('home') : route('admin.users.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop
