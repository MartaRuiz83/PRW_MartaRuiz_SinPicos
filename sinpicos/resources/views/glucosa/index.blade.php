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
          Bienvenido, {{ Auth::user()->name }}.
        </h2>
        <small class="text-muted">
          Datos de {{ \Carbon\Carbon::parse($date)->format('d-m-Y') }}
        </small>
      </div>

      {{-- Botón día siguiente (si aplica) --}}
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

  {{-- TABLA DE REGISTROS --}}
  @php
    // Rangos objetivo
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
                // ¿Está en rango según el momento?
                if($g->momento === 'ANTES') {
                  $inRange = $g->nivel_glucosa >= $preMin && $g->nivel_glucosa <= $preMax;
                } else {
                  $inRange = $g->nivel_glucosa < $postMax;
                }
              @endphp
              <tr>
                <td>{{ $g->hora }}</td>
                <td>
                  <span class="badge {{ $g->momento=='ANTES'?'bg-success':'bg-warning' }}">
                    {{ $g->momento }}
                  </span>
                </td>
                <td class="{{ $inRange ? 'text-success' : 'text-danger' }} fw-bold">
                  {{ $g->nivel_glucosa }} mg/dL
                </td>
                <td class="text-center">
                  <a href="{{ route('glucosa.edit',$g) }}" class="btn btn-sm btn-outline-warning">
                    <i class="ri-edit-2-fill"></i>
                  </a>
                  <form action="{{ route('glucosa.destroy',$g) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('¿Eliminar este registro?');">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-outline-danger">
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
<script>
  // Preparar datos para ECharts
  const data = @json(
    $glucosas->map(fn($g)=>[
      'label'=> $g->hora,
      'value'=> $g->nivel_glucosa,
    ])
  );
  const chartDom = document.getElementById('glucose-chart');
  const myChart  = echarts.init(chartDom);
  const option   = {
    color: ['#b91c1c'],
    tooltip: { trigger: 'axis' },
    xAxis: {
      type: 'category',
      data: data.map(d=>d.label),
      axisLabel: { color: '#555' }
    },
    yAxis: {
      type: 'value',
      name: 'mg/dL',
      axisLabel: { color: '#555' }
    },
    series: [{
      data: data.map(d=>d.value),
      type: 'line',
      smooth: true,
      areaStyle: { color: 'rgba(185,28,28,0.2)' }
    }]
  };
  myChart.setOption(option);
</script>
@endpush
