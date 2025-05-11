@extends('adminlte::page')

@section('title', 'Recomendaciones')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1 class="h3">Recomendaciones</h1>
    <a href="{{ route('admin.recomendations.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Crear nueva
    </a>
</div>
@stop

@section('content')
<div class="container-fluid mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($recs->isEmpty())
        <div class="alert alert-info text-center">
            No hay recomendaciones aún. 
            <a href="{{ route('admin.recomendations.create') }}">¡Crea tu primera recomendación!</a>
        </div>
    @else
        <div class="row gy-4">
            @foreach($recs as $rec)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $rec->titulo }}</h5>
                            <p class="card-text text-muted mb-4">
                                {{ \Illuminate\Support\Str::limit($rec->descripcion, 120) }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('admin.recomendations.show', $rec) }}"
                                   class="btn btn-sm btn-outline-info me-1">
                                    <i class="fas fa-eye"></i> Ver
                                </a>
                                <a href="{{ route('admin.recomendations.edit', $rec) }}"
                                   class="btn btn-sm btn-outline-warning me-1">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('admin.recomendations.destroy', $rec) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar esta recomendación?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i> Borrar
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="card-footer text-end text-muted">
                            {{ $rec->created_at->diffForHumans() }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $recs->links() }}
        </div>
    @endif
</div>
@stop
