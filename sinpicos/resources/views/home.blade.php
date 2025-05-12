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

<div class="container py-4">
  <h2 class="h5 mb-4" style="color: #7d3ced;">
  Consejos del día
</h2>


  <div class="row g-3">
    @foreach($tips as $tip)
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0 rounded-2">
          <div class="card-body d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center mb-2">
                <i class="ri-check-line text-success fs-3 me-2"></i>
                <h3 class="h6 mb-0">{{ $tip->recomendation->titulo }}</h3>
              </div>
              <p class="small text-muted mb-0">
                {{ $tip->recomendation->descripcion }}
              </p>
            </div>
            <form action="{{ route('admin.tips.showed', $tip) }}" method="POST" class="mt-3 text-end">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-success">
                Marcar como visto
              </button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

@php
    $ranges = [
        'carbohydrates' => ['min' => 130, 'max' => 180],
        'proteins'      => ['min' => 75,  'max' => 100],
        'fats'          => ['min' => 50,  'max' => 70],
        'calories'      => ['min' => 1500, 'max' => 2000],
    ];

    function getColorClass($value, $min, $max) {
        if ($value < $min) return 'text-success';      // Verde
        if ($value >= $min && $value < $max) return 'text-warning'; // Naranja
        return 'text-danger';                          // Rojo
    }
@endphp


 {{-- Resumen del Día --}}
<div class="row row-cols-1 row-cols-md-4 g-4 mb-4">
  
  <div class="col">
    <div class="card h-100">
      <div class="card-body text-center">
        <h5 class="card-title">Carbohidratos</h5>
        <h2 class="fw-bold {{ getColorClass($carbohydrates, $ranges['carbohydrates']['min'], $ranges['carbohydrates']['max']) }}">
          {{ $carbohydrates }} g
        </h2>
        <p class="text-muted mb-0">de 130 - 180 g</p>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card h-100">
      <div class="card-body text-center">
        <h5 class="card-title">Proteínas</h5>
        <h2 class="fw-bold {{ getColorClass($proteins, $ranges['proteins']['min'], $ranges['proteins']['max']) }}">
          {{ $proteins }} g
        </h2>
        <p class="text-muted mb-0">de 75 - 100 g</p>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card h-100">
      <div class="card-body text-center">
        <h5 class="card-title">Grasas</h5>
        <h2 class="fw-bold {{ getColorClass($fats, $ranges['fats']['min'], $ranges['fats']['max']) }}">
          {{ $fats }} g
        </h2>
        <p class="text-muted mb-0">de 50 - 70 g</p>
      </div>
    </div>
  </div>

  <div class="col">
    <div class="card h-100">
      <div class="card-body text-center">
        <h5 class="card-title">Calorías</h5>
        <h2 class="fw-bold {{ getColorClass($calories, $ranges['calories']['min'], $ranges['calories']['max']) }}">
          {{ round($calories, 1) }} kcal
        </h2>
        <p class="text-muted mb-0">de 21500 - 2000 kcal</p>
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
              $meal->calories += ($ing->calories ?? 0) * $q / 100; 
            }
          @endphp
          <p class="mb-0 text-muted">
            Carbohidratos: {{ round($c,1) }} g |
            Proteinas:   {{ round($p,1) }} g |
            Grasas: {{ round($f,1) }} g |
            Calorías: {{ round($meal->calories,1) }} kcal
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
