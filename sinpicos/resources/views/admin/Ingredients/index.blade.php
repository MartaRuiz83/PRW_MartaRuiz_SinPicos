{{-- resources/views/admin/ingredients/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Ingredientes')

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-dark font-weight-bold">Ingredientes</h1>
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

    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre</th>
                        <th>Carbohidratos (g)</th>
                        <th>Proteínas (g)</th>
                        <th>Grasas (g)</th>
                        <th>Calorías</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($ingredients as $ingredient)
                    <tr>
                        <td>{{ $ingredient->id }}</td>
                        <td>{{ $ingredient->name }}</td>
                        <td>{{ $ingredient->carbohydrates }}</td>
                        <td>{{ $ingredient->proteins }}</td>
                        <td>{{ $ingredient->fats }}</td>
                        <td>{{ $ingredient->calories }}</td>
                        <td class="text-center">
                            {{-- Ver --}}
                            <a href="{{ route('admin.ingredients.show', $ingredient) }}"
                               class="text-info mr-3" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            {{-- Editar --}}
                            <a href="{{ route('admin.ingredients.edit', $ingredient) }}"
                               class="text-warning mr-3" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            {{-- Eliminar --}}
                            <form action="{{ route('admin.ingredients.destroy', $ingredient) }}"
                                  method="POST" style="display:inline"
                                  onsubmit="return confirm('¿Eliminar ingrediente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger p-0 border-0 bg-transparent"
                                        title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">
                            No hay ingredientes registrados.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $ingredients->links() }}
    </div>
@stop
