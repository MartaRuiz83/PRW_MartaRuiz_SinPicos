{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('content')




<div class="container py-4">
  <h1 class="h3 mb-4">Bienvenido, {{ Auth::user()->name }}.</h1>
  <a href="{{ route('home', ['date' => $dates['yesterday']->format("Y-m-d")]) }}">
  <i class="ri-arrow-left-line"></i>
</a>
  <p class="text-muted mb-4">Datos de {{ $dates["today"]->format("d-m-Y") }}</p>
<div class="container py-4">
  @if ($dates['tomorrow'] != null)
  <a href="{{ route('home', ['date' => $dates['tomorrow']->format("Y-m-d")]) }}">
  <i class="ri-arrow-right-line"></i>
</a>
  @endif

  


  {{-- Resumen del Día --}}
  <div class="row row-cols-1 row-cols-md-3 g-4 mb-4">
    <div class="col">
      <div class="card h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Carbohidratos</h5>
          <h2 class="fw-bold">{{$carbohydrates}} g</h2>
          <p class="text-muted mb-0">de 180 g objetivo</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Proteínas</h5>
          <h2 class="fw-bold">{{$proteins}} g</h2>
          <p class="text-muted mb-0">de 90 g objetivo</p>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card h-100">
        <div class="card-body text-center">
          <h5 class="card-title">Grasas</h5>
          <h2 class="fw-bold">{{$fats}} g</h2>
          <p class="text-muted mb-0">de 60 g objetivo</p>
        </div>
      </div>
    </div>
  </div>

  {{-- Gráfico de macronutrientes --}}
  <div class="card mb-4">
    <div class="card-body" style="height:300px;">
      <div id="macronutrientesChart" class="w-100 h-100"></div>
    </div>
  </div>

  {{-- Registro de Comidas --}}
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Registro de Comidas</h2>
    <a href="{{ route('meals.create') }}" class="btn btn-primary">
      <i class="ri-add-line"></i> Añadir Comida
    </a>
  </div>

  {{-- Listado de comidas --}}
  @forelse($meals as $meal)
    <div class="card mb-3">
      <div class="card-body d-flex justify-content-between align-items-center">
        <div>
          <p class="mb-1"><strong>
            @foreach($meal->ingredients as $ing)
              {{ $ing->name }} ({{ $ing->pivot->quantity }} g)@if(!$loop->last), @endif
            @endforeach
          </strong></p>
          <p class="mb-1 text-muted">
            {{ \Carbon\Carbon::parse($meal->date)->format('d/m/Y') }}
            —
            {{ \Carbon\Carbon::parse($meal->time)->format('H:i') }}
          </p>
          @php
            $c = $p = $f = 0;
            foreach($meal->ingredients as $ing) {
              $q = $ing->pivot->quantity;
              $c += ($ing->carbohydrates ?? 0) * $q / 100;
              $p += ($ing->proteins      ?? 0) * $q / 100;
              $f += ($ing->fats          ?? 0) * $q / 100;
            }
          @endphp
          <p class="mb-0 text-muted">
            Carbos: {{ round($c,1) }} g |
            Prot:   {{ round($p,1) }} g |
            Grasas: {{ round($f,1) }} g
          </p>
        </div>
        <div class="d-flex align-items-center">
          {{-- Editar (solo icono naranja) --}}
          <a href="{{ route('admin.meals.edit', $meal) }}"
             class="btn btn-link text-warning p-0 me-3"
             title="Editar">
            <i class="ri-pencil-line fs-4"></i>
          </a>
          {{-- Eliminar (solo icono rojo) --}}
          <form action="{{ route('admin.meals.destroy', $meal) }}"
                method="POST"
                onsubmit="return confirm('¿Eliminar esta comida?');">
            @csrf @method('DELETE')
            <button type="submit"
                    class="btn btn-link text-danger p-0"
                    title="Eliminar">
              <i class="ri-delete-bin-2-line fs-4"></i>
            </button>
          </form>
        </div>
      </div>
    </div>
  @empty
    <p class="text-muted">No hay comidas registradas.</p>
  @endforelse
</div>
@endsection

@push('scripts')
<script>
  const chart = echarts.init(document.getElementById('macronutrientesChart'));
  chart.setOption({
    series:[{
      type:'pie',
      radius:['40%','70%'],
      label:{ formatter:'{b}: {c} g' },
      data:[
        { value:{{$carbohydrates}}, name:'Carbohidratos' },
        { value:{{$proteins}},  name:'Proteínas' },
        { value:{{$fats}},  name:'Grasas' }
      ]
    }]
  });
</script>
@endpush
