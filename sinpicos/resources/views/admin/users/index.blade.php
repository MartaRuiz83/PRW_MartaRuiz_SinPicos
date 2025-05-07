{{-- resources/views/admin/users/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-dark font-weight-bold">Usuarios</h1>
    <a href="{{ route('admin.users.create') }}"
       class="btn"
       style="background-color: #559ae4; color: #fff; border: none;
              width: 40px; height: 40px; display: flex;
              align-items: center; justify-content: center;
              border-radius: 4px;"
       title="Nuevo Usuario">
        <i class="fas fa-user-plus"></i>
    </a>
</div>
@stop

@section('content')
@if(session('success'))
    <x-adminlte-alert theme="success" title="¡Éxito!">
        {{ session('success') }}
    </x-adminlte-alert>
@endif

<ul class="list-group">
    @forelse($users as $user)
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-1">
                    {{ $user->name }}
                    <small class="text-muted">#{{ $user->id }}</small>
                </h5>
                <p class="mb-0 text-sm">
                    <i class="fas fa-envelope mr-1" style="color: #869395;"></i>
                    <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                </p>
                <div class="mt-1">
                    <span class="badge badge-info mr-1">{{ ucfirst($user->rol) }}</span>
                    <span class="badge" style="background-color: #FF8C00; color: #fff;">
                        {{ ucfirst($user->tipo_diabetes) }}
                    </span>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <a href="{{ route('admin.users.show', $user) }}"
                   class="text-info mr-4"
                   title="Ver usuario"
                   style="font-size: 1.1rem;">
                    <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="text-warning mr-3"
                   title="Editar usuario"
                   style="font-size: 1.1rem;">
                    <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}"
                      method="POST"
                      onsubmit="return confirm('¿Eliminar usuario?');"
                      style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="text-danger ml-3 p-0 border-0 bg-transparent"
                            title="Eliminar usuario"
                            style="font-size: 1.1rem;">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </form>
            </div>
        </li>
    @empty
        <li class="list-group-item text-center text-muted">
            No hay usuarios registrados.
        </li>
    @endforelse
</ul>

<div class="mt-3 d-flex justify-content-center">
    {{ $users->links() }}
</div>
@stop
