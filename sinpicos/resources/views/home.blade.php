{{-- resources/views/home.blade.php --}}
@extends('layouts.app')

@section('content')




<div class="container py-4">
  <div class="card shadow-sm rounded-lg border-0">
    <div class="card-body d-flex align-items-center justify-content-between">
      
      <!-- Flecha al día anterior -->
      <a 
        href="{{ route('home', ['date' => $dates['yesterday']->format('Y-m-d')]) }}" 
        class="btn btn-outline-secondary btn-lg me-3" 
        title="Día anterior"
      >
        <i class="ri-arrow-left-line fs-4"></i>
      </a>

      <!-- Centramos nombre y fecha -->
      <div class="text-center flex-grow-1">
        <h1 class="h3 fw-bold mb-1">
          Bienvenido, {{ Auth::user()->name }}.
        </h1>
        <p class="text-muted mb-0">
          Datos de {{ $dates['today']->format('d-m-Y') }}
        </p>
      </div>

      <!-- Flecha al día siguiente (solo si existe) -->
      @if ($dates['tomorrow'])
      <a 
        href="{{ route('home', ['date' => $dates['tomorrow']->format('Y-m-d')]) }}" 
        class="btn btn-outline-secondary btn-lg ms-3" 
        title="Siguiente día"
      >
        <i class="ri-arrow-right-line fs-4"></i>
      </a>
      @endif

    </div>
  </div>
</div>

<h2 class="h5 mb-3">Consejos del día</h2>
@foreach($tips as $tip)
<div class="container py-4"></div>
  <div class="card shadow-sm rounded-lg border-0">
    <div class="card-body">
      <p class="mb-0">{{ $tip->recomendation->titulo}}</p>
      <p class="mb-0 text-muted">{{ $tip->recomendation->descripcion}}</p>
     
      <form action="{{ route('admin.tips.showed', $tip) }}"
            method="POST">
            @csrf
        <button type="submit"
                class="btn btn-link text-danger p-0"
                title="Eliminar">
          <i class="ri-delete-bin-2-line fs-4"></i>
        </button>
      </form>
    </div>
  </div>
@endforeach




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
