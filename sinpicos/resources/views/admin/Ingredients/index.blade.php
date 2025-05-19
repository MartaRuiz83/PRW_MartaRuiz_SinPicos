{{-- resources/views/admin/ingredients/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Ingredientes')

@section('css')
  {{-- DataTables CSS --}}
  <link 
    rel="stylesheet" 
    href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css"
  >
@stop

@section('content_header')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-dark font-weight-bold">Ingredientes</h1>
    <a href="{{ route('admin.ingredients.create') }}"
       class="btn"
       style="background-color: #559ae4; color: #fff; border: none;
              width: 40px; height: 40px; display: flex;
              align-items: center; justify-content: center;
              border-radius: 4px;"
       title="Nuevo Ingrediente">
        <i class="fas fa-carrot fs-5"></i>
        <i class="fas fa-plus ms-1"></i>
    </a>
</div>
@stop

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table id="ingredients-table" class="table table-striped table-hover table-bordered mb-0">
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
                @foreach($ingredients as $ingredient)
                    <tr>
                        <td>{{ $ingredient->id }}</td>
                        <td>{{ $ingredient->name }}</td>
                        <td>{{ $ingredient->carbohydrates }}</td>
                        <td>{{ $ingredient->proteins }}</td>
                        <td>{{ $ingredient->fats }}</td>
                        <td>{{ $ingredient->calories }}</td>
                        <td class="text-center">
                            <a href="{{ route('admin.ingredients.show', $ingredient) }}"
                               class="text-info me-3" title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.ingredients.edit', $ingredient) }}"
                               class="text-warning me-3" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.ingredients.destroy', $ingredient) }}"
                                  method="POST"
                                  class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="text-danger p-0 border-0 bg-transparent"
                                        title="Eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('js')
  {{-- jQuery --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  {{-- DataTables JS --}}
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
  {{-- SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function() {
      // Inicializar DataTable
      $('#ingredients-table').DataTable({
        paging:       true,
        searching:    true,
        ordering:     true,
        info:         true,
        lengthChange: false,
        pageLength:   10,
        language: {
          url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json'
        },
        columnDefs: [
          { orderable: false, targets: -1 } // Desactiva orden en columna Acciones
        ]
      });

      // Mostrar SweetAlert si hay mensaje de éxito en sesión
      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: @json(session('success')),
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#28a745' // botón verde
        });
      @endif

      // Confirmación de eliminación con SweetAlert2
      $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
          title: '¿Eliminar este ingrediente?',
          text: "¡Esta acción no se puede deshacer!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#dc3545', // botón rojo
          cancelButtonColor: '#6c757d'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  </script>
@stop
