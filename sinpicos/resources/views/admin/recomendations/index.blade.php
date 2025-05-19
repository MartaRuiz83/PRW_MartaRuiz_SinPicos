{{-- resources/views/admin/recomendations/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Recomendaciones')

@section('css')
  {{-- DataTables CSS --}}
  <link 
    rel="stylesheet" 
    href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css"
  >
@stop

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
          {{-- El alert con SweetAlert2 lo dispara en JS --}}
      @endif

      @if($recs->isEmpty())
          <div class="alert alert-info text-center">
              No hay recomendaciones aún. 
              <a href="{{ route('admin.recomendations.create') }}">¡Crea tu primera recomendación!</a>
          </div>
      @else
          <table id="recs-table" class="table table-striped table-bordered mb-0">
            <thead>
              <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Descripción</th>
                <th>Creado</th>
                <th class="text-center">Acciones</th>
              </tr>
            </thead>
            <tbody>
              @foreach($recs as $rec)
                <tr>
                  <td>{{ $rec->id }}</td>
                  <td>{{ $rec->titulo }}</td>
                  <td>{{ \Illuminate\Support\Str::limit($rec->descripcion, 80) }}</td>
                  <td>{{ $rec->created_at->format('d/m/Y') }}</td>
                  <td class="text-center">
                    <a href="{{ route('admin.recomendations.show', $rec) }}" class="text-info me-2" title="Ver">
                      <i class="fas fa-eye"></i>
                    </a>
                    <a href="{{ route('admin.recomendations.edit', $rec) }}" class="text-warning me-2" title="Editar">
                      <i class="fas fa-edit"></i>
                    </a>
                    <form action="{{ route('admin.recomendations.destroy', $rec) }}"
                          method="POST"
                          class="d-inline delete-form">
                      @csrf
                      @method('DELETE')
                      <button type="submit"
                              class="btn btn-link p-0 text-danger"
                              title="Eliminar">
                        <i class="fas fa-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
      @endif
  </div>
@stop

@section('js')
  {{-- jQuery y DataTables --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

  {{-- SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(function() {
      // Inicializar DataTable
      $('#recs-table').DataTable({
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
          { orderable: false, targets: -1 } // acciones sin orden
        ]
      });

      // Mostrar Toast de éxito con SweetAlert2 si hay sesión
      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: @json(session('success')),
          confirmButtonText: 'Aceptar'
        });
      @endif

      // Confirmación de eliminación
      $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
          title: '¿Eliminar esta recomendación?',
          text: "¡Esta acción no se puede deshacer!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  </script>
@stop
