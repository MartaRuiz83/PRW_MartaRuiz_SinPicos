@extends('layouts.app')

@section('content')
<div class="container py-5">

  {{-- ENCABEZADO DESTACADO --}}
  <div class="card mb-4 shadow-sm border-0 rounded-lg">
    <div class="card-body d-flex align-items-center">

      {{-- Botón día anterior --}}
      <a href="{{ route('glucosa.index', ['date' => $yesterday]) }}"
         class="btn btn-outline-danger btn-lg me-4" title="Día anterior">
        <i class="ri-arrow-left-line fs-3"></i>
      </a>

      {{-- Texto centrado --}}
      <div class="text-center flex-grow-1">
        <h2 class="mb-1" style="color:#b91c1c;">
          Aquí tienes tu Control de Glucosa, {{ Auth::user()->name }}
          <i class="ri-drop-fill" style="color:#e74c3c;"></i>
        </h2>
        <small class="text-muted">
          Datos de {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
        </small>
      </div>

      {{-- Botón día siguiente --}}
      @if($tomorrow)
      <a href="{{ route('glucosa.index', ['date' => $tomorrow]) }}"
         class="btn btn-outline-danger btn-lg ms-4" title="Siguiente día">
        <i class="ri-arrow-right-line fs-3"></i>
      </a>
      @endif

    </div>
  </div>

  {{-- Botón Nueva Medición --}}
  <div class="mb-4 text-end">
    <a href="{{ route('glucosa.create') }}" class="btn btn-danger">
      <i class="ri-add-circle-fill"></i> Nueva Medición
    </a>
  </div>

  {{-- ESTADÍSTICAS --}}
  @php
    $levels = $glucosas->pluck('nivel_glucosa');
    $avg = $levels->avg() ?: 0;
    $min = $levels->min() ?: 0;
    $max = $levels->max() ?: 0;
  @endphp
  <div class="row text-center mb-5">
    <div class="col-md-4 mb-3">
      <div class="card border-danger shadow-sm">
        <div class="card-body">
          <h6 class="text-danger">Promedio</h6>
          <h3>{{ number_format($avg,1) }} mg/dL</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-danger shadow-sm">
        <div class="card-body">
          <h6 class="text-danger">Mínimo</h6>
          <h3>{{ $min }} mg/dL</h3>
        </div>
      </div>
    </div>
    <div class="col-md-4 mb-3">
      <div class="card border-danger shadow-sm">
        <div class="card-body">
          <h6 class="text-danger">Máximo</h6>
          <h3>{{ $max }} mg/dL</h3>
        </div>
      </div>
    </div>
  </div>

  {{-- GRÁFICA EVOLUCIÓN --}}
  <div class="card mb-5 shadow-sm">
    <div class="card-body">
      <div id="glucose-chart" style="height:300px;"></div>
    </div>
  </div>

  {{-- CONSEJOS DE SEGURIDAD --}}
  <div class="card mb-5 border-danger shadow-sm">
    <div class="card-header bg-danger text-white">
      <i class="ri-shield-check-fill me-2"></i> Consejos de Seguridad
    </div>
    <div class="card-body">
      <div class="row">
        {{-- Hipoglucemia --}}
        <div class="col-md-6 mb-4">
          <h5 class="text-danger"><i class="ri-heart-pulse-fill me-1"></i> ¿Hipoglucemia? (&lt; 70 mg/dL)</h5>
          <ul class="ps-3 mb-0">
            <li>Ingiere 15 g de carbohidratos de rápida absorción (glucosa, zumo).</li>
            <li>Espera 15 min y vuelve a medir.</li>
            <li>Si persiste, repite y contacta a tu médico.</li>
          </ul>
        </div>
        {{-- Hiperglucemia --}}
        <div class="col-md-6 mb-4">
          <h5 class="text-danger"><i class="ri-flashlight-fill me-1"></i> ¿Hiperglucemia? (&gt; 180 mg/dL)</h5>
          <ul class="ps-3 mb-0">
            <li>Realiza ejercicio ligero (paseo 10–15 min).</li>
            <li>Hidrátate con agua sin azúcar.</li>
            <li>Revisa tu plan de insulina.</li>
          </ul>
        </div>
      </div>
    </div>
  </div>

  {{-- TABLA DE REGISTROS --}}
  @php
    $preMin  = 80;
    $preMax  = 130;
    $postMax = 180;
  @endphp
  <div class="card shadow-sm">
    <div class="card-body">
      <h4 class="mb-3" style="color:#b91c1c;">Historial</h4>
      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead class="table-danger">
            <tr>
              <th>Hora</th>
              <th>Momento</th>
              <th>Nivel (mg/dL)</th>
              <th class="text-center">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @forelse($glucosas as $g)
              @php
                $normal = $g->momento==='ANTES'
                          ? ($g->nivel_glucosa >= $preMin && $g->nivel_glucosa <= $preMax)
                          : ($g->nivel_glucosa < $postMax);
                $color  = $g->nivel_glucosa < 70
                          ? 'text-danger'
                          : ($normal ? 'text-success' : 'text-danger');
              @endphp
              <tr>
                <td>{{ $g->hora }}</td>
                <td>
                  @if($g->momento === 'ANTES')
                    <i class="ri-apple-fill text-danger me-1"></i><small class="text-muted">Antes</small>
                  @else
                    <i class="ri-apple-line text-danger me-1"></i><small class="text-muted">Después</small>
                  @endif
                </td>
                <td class="{{ $color }} fw-bold">
                  {{ $g->nivel_glucosa }} mg/dL
                </td>
                <td class="text-center">
                  <a href="{{ route('glucosa.edit', $g) }}"
                     class="btn btn-sm btn-outline-warning"
                     title="Editar">
                    <i class="ri-edit-2-fill"></i>
                  </a>
                  <form action="{{ route('glucosa.destroy', $g) }}"
                        method="POST"
                        class="d-inline delete-form">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger" title="Eliminar">
                      <i class="ri-delete-bin-5-fill"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center text-muted py-4">
                  No hay registros para este día.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@push('scripts')
  <!-- 1) Incluir SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <!-- 2) Tus scripts de ECharts -->
  <script>
    const data = @json($glucosas->map(fn($g)=>['label'=>$g->hora,'value'=>$g->nivel_glucosa]));
    const myChart = echarts.init(document.getElementById('glucose-chart'));
    myChart.setOption({
      color:['#b91c1c'],
      tooltip:{ trigger:'axis' },
      xAxis:{ type:'category', data: data.map(d=>d.label), axisLabel:{ color:'#555' } },
      yAxis:{ type:'value', name:'mg/dL', axisLabel:{ color:'#555' } },
      series:[{ data: data.map(d=>d.value), type:'line', smooth:true, areaStyle:{ color:'rgba(185,28,28,0.2)' } }]
    });
  </script>

  <!-- 3) SweetAlert2 para éxito y confirmación de borrado -->
  <script>
    // Mostrar alerta de éxito si no viene de editar
    @if(session('success') && session('success') !== 'Registro actualizado correctamente.')
      Swal.fire({
        icon:'success',
        title:'¡Éxito!',
        text:@json(session('success')),
        confirmButtonText:'Aceptar',
        confirmButtonColor:'#28a745'
      });
    @endif

    // Confirmación de borrado (botón rojo)
    document.querySelectorAll('.delete-form').forEach(form=>{
      form.addEventListener('submit', e=>{
        e.preventDefault();
        Swal.fire({
          title:'¿Eliminar este registro?',
          text:"¡Esta acción no se puede deshacer!",
          icon:'warning',
          showCancelButton:true,
          confirmButtonText:'Sí, eliminar',
          cancelButtonText:'Cancelar',
          confirmButtonColor:'#dc3545',
          cancelButtonColor:'#6c757d'
        }).then(result=>{ if(result.isConfirmed) form.submit() });
      });
    });
  </script>
@endpush
