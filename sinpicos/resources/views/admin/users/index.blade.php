{{-- resources/views/admin/users/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Usuarios')

@section('css')
  {{-- DataTables CSS --}}
  <link 
    rel="stylesheet" 
    href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css"
  >
@stop

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
    <div class="card">
      <div class="card-body table-responsive p-0">
        <table id="users-table" class="table table-striped table-hover table-bordered mb-0">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nombre</th>
              <th>Email</th>
              <th>Rol</th>
              <th>Tipo Diabetes</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
          @foreach($users as $user)
            <tr>
              <td>{{ $user->id }}</td>
              <td>{{ $user->name }}</td>
              <td><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></td>
              <td>{{ ucfirst($user->rol) }}</td>
              <td>{{ ucfirst($user->tipo_diabetes) }}</td>
              <td class="text-center">
                <a href="{{ route('admin.users.show', $user) }}"
                   class="text-info me-2"
                   title="Ver usuario">
                  <i class="fas fa-eye"></i>
                </a>
                <a href="{{ route('admin.users.edit', $user) }}"
                   class="text-warning me-2"
                   title="Editar usuario">
                  <i class="fas fa-edit"></i>
                </a>
                <form action="{{ route('admin.users.destroy', $user) }}"
                      method="POST"
                      class="d-inline delete-form">
                  @csrf
                  @method('DELETE')
                  <button type="submit"
                          class="btn btn-link p-0 text-danger"
                          title="Eliminar usuario">
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
  {{-- jQuery y DataTables --}}
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

  {{-- SweetAlert2 --}}
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    $(document).ready(function() {
      // Inicializar DataTables
      $('#users-table').DataTable({
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
          { orderable: false, targets: -1 } // desactivar orden en Acciones
        ]
      });

      // Mostrar SweetAlert al hacer redirect con session('success')
      @if(session('success'))
        Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: @json(session('success')),
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#28a745' // botón verde
        });
      @endif

      // Confirmación de borrado con SweetAlert
      $('.delete-form').on('submit', function(e) {
        e.preventDefault();
        const form = this;
        Swal.fire({
          title: '¿Eliminar este usuario?',
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
