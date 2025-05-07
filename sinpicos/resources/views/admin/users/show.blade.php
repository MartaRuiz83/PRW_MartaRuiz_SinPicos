{{-- resources/views/admin/users/show.blade.php --}}
@extends('adminlte::page')

@section('title', 'Ver Usuario')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 font-weight-bold" style="color: #559ae4;">Usuario #{{ $user->id }}</h1>
    <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill me-2">
            Volver
        </a>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning rounded-pill">
            Editar
        </a>
    </div>
</div>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-header bg-gradient-primary">
                <h3 class="card-title text-white">Detalles del Usuario</h3>
            </div>
            <div class="card-body">
                <dl class="row mb-3">
                    <dt class="col-sm-4 text-muted">Nombre</dt>
                    <dd class="col-sm-8">{{ $user->name }}</dd>

                    <dt class="col-sm-4 text-muted">Email</dt>
                    <dd class="col-sm-8">
                        <i class="fas fa-envelope text-info me-1"></i>
                        <a href="mailto:{{ $user->email }}" class="text-decoration-none">{{ $user->email }}</a>
                    </dd>

                    <dt class="col-sm-4 text-muted">Rol</dt>
                    <dd class="col-sm-8">
                        <span class="badge badge-info">{{ ucfirst($user->rol) }}</span>
                    </dd>

                    <dt class="col-sm-4 text-muted">Tipo Diabetes</dt>
                    <dd class="col-sm-8">
                        <span class="badge badge-warning">{{ ucfirst($user->tipo_diabetes) }}</span>
                    </dd>
                </dl>
                <p class="text-sm text-muted">
                    Creado: {{ $user->created_at->format('d/m/Y H:i') }}<br>
                    Última actualización: {{ $user->updated_at->format('d/m/Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</div>
@stop
