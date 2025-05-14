@extends('layouts.app')

@section('content')

<div class="container py-4">
  <div class="card shadow-sm rounded-lg border-0">
    <div class="card-body d-flex align-items-center justify-content-between">
      <!-- Flecha al día anterior -->
      <a href="{{ route('home', ['date' => $dates['yesterday']->format('Y-m-d')]) }}"
         class="btn btn-outline-secondary btn-lg me-3" title="Día anterior">
        <i class="ri-arrow-left-line fs-4"></i>
      </a>
      <!-- Título y fecha -->
      <div class="text-center flex-grow-1">
        <h1 class="h3 fw-bold mb-1">Bienvenido, {{ Auth::user()->name }}.</h1>
        <p class="text-muted mb-0">Datos de {{ $dates['today']->format('d-m-Y') }}</p>
      </div>
      <!-- Flecha al día siguiente -->
      @if($dates['tomorrow'])
        <a href="{{ route('home', ['date' => $dates['tomorrow']->format('Y-m-d')]) }}"
           class="btn btn-outline-secondary btn-lg ms-3" title="Siguiente día">
          <i class="ri-arrow-right-line fs-4"></i>
        </a>
      @endif
    </div>
  </div>
</div>

<div class="container py-4">
  <h2 class="h5 mb-4" style="color: #7d3ced;">Consejos del día</h2>
  <div class="row g-3">
    @foreach($tips->take(3) as $tip)
      <div class="col-12 col-md-6 col-lg-4">
        <div class="card h-100 shadow-sm border-0 rounded-2">
          <div class="card-body d-flex flex-column justify-content-between">
            <div>
              <div class="d-flex align-items-center mb-2">
                <i class="ri-check-line text-success fs-3 me-2"></i>
                <h3 class="h6 mb-0">{{ $tip->recomendation->titulo }}</h3>
              </div>
              <p class="small text-muted mb-0">{{ $tip->recomendation->descripcion }}</p>
            </div>
            <form action="{{ route('admin.tips.showed', $tip) }}" method="POST" class="mt-3 text-end">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-success">Marcar como visto</button>
            </form>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>

@php
  // Rangos para colores
  $ranges = [
    'carbohydrates'=> ['min'=>130,'max'=>180],
    'proteins'     => ['min'=>75, 'max'=>100],
    'fats'         => ['min'=>50, 'max'=>70],
    'calories'     => ['min'=>1500,'max'=>2000],
  ];
  function getColorClass($v,$min,$max){
    if($v<$min) return 'text-success';
    if($v<$max) return 'text-warning';
    return 'text-danger';
  }
  // Agrupamos comidas y definimos orden
  $grouped = $meals->groupBy('meal_type');
  $order   = ['Desayuno','Almuerzo','Snack','Cena'];
@endphp

{{-- Resumen del Día --}}
<div class="row row-cols-1 row-cols-md-4 g-4 mb-4">
  @foreach($ranges as $label => $r)
    <div class="col">
      <div class="card h-100">
        <div class="card-body text-center">
          @switch($label)
            @case('carbohydrates')
              <h5 class="card-title">Carbohidratos</h5>
            @break
            @case('proteins')
              <h5 class="card-title">Proteínas</h5>
            @break
            @case('fats')
              <h5 class="card-title">Grasas</h5>
            @break
            @case('calories')
              <h5 class="card-title">Calorías</h5>
            @break
          @endswitch

          <h2 class="fw-bold {{ getColorClass($$label, $r['min'], $r['max']) }}">
            {{ $label==='calories' ? round($$label,1).' kcal' : $$label.' g' }}
          </h2>
          <p class="text-muted mb-0">
            de {{ $r['min'] }} - {{ $r['max'] }}
            {{ $label==='calories' ? 'kcal' : 'g' }}
          </p>
        </div>
      </div>
    </div>
  @endforeach
</div>

{{-- Leyenda ultra-compacta --}}
<div class="d-flex justify-content-center gap-4 mb-4 small">
  <span class="d-flex align-items-center">
    <i class="ri-checkbox-blank-circle-fill text-success fs-2 me-1"></i>Verde: margen
  </span>
  <span class="d-flex align-items-center">
    <i class="ri-checkbox-blank-circle-fill text-warning fs-2 me-1"></i>Naranja: precaución
  </span>
  <span class="d-flex align-items-center">
    <i class="ri-checkbox-blank-circle-fill text-danger fs-2 me-1"></i>Rojo: excedido
  </span>
</div>




{{-- Gráfico de macronutrientes --}}
<div class="card mb-4">
  <div class="card-body" style="height:300px;">
    <div id="macronutrientesChart" class="w-100 h-100"></div>
  </div>
</div>

{{-- Registro de Comidas agrupado por tipo --}}
<div class="container mb-5">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Registro de Comidas</h2>
    <a href="{{ route('meals.create') }}" class="btn btn-primary">
      <i class="ri-add-line"></i> Añadir Comida
    </a>
  </div>

  @forelse($order as $type)
    @if(isset($grouped[$type]) && $grouped[$type]->isNotEmpty())
      @php
        // Calculamos calorías totales para el grupo
        $sumCalories = $grouped[$type]->reduce(function($carry, $meal){
          return $carry + $meal->ingredients->reduce(function($c2, $ing){
            return $c2 + (($ing->calories ?? 0) * $ing->pivot->quantity / 100);
          }, 0);
        }, 0);
      @endphp

      <div class="mb-4">
        <div class="d-flex align-items-center mb-2">
          @php
            $icon = match($type) {
              'Desayuno' => 'ri-sun-line text-warning',
              'Almuerzo' => 'ri-restaurant-line text-info',
              'Snack'    => 'ri-apple-line text-success',
              'Cena'     => 'ri-moon-line text-secondary',
              default    => 'ri-clipboard-line text-muted',
            };
          @endphp
          <i class="{{ $icon }} fs-3 me-2"></i>
          <h4 class="fw-bold mb-0" style="background: linear-gradient(90deg, #7d3ced, #c77dff); -webkit-background-clip: text; color: transparent;">
            {{ $type }}
          </h4>
          <span class="badge bg-light text-danger ms-3">Total {{ round($sumCalories,1) }} kcal</span>
        </div>

        @foreach($grouped[$type] as $meal)
          @php
            // Calculamos macros de esta comida
            $mealCarbs = $meal->ingredients->reduce(fn($c,$ing)=> $c + (($ing->carbohydrates ?? 0) * $ing->pivot->quantity/100), 0);
            $mealProt  = $meal->ingredients->reduce(fn($c,$ing)=> $c + (($ing->proteins      ?? 0) * $ing->pivot->quantity/100), 0);
            $mealFats  = $meal->ingredients->reduce(fn($c,$ing)=> $c + (($ing->fats          ?? 0) * $ing->pivot->quantity/100), 0);
            $mealCals  = $meal->ingredients->reduce(fn($c,$ing)=> $c + (($ing->calories      ?? 0) * $ing->pivot->quantity/100), 0);
          @endphp

          <div class="card mb-2">
            <div class="card-body d-flex justify-content-between align-items-start">
              <div>
                @if($meal->description)
                  <h5 class="mb-1" style="color: #000;">{{ $meal->description }}</h5>
                @endif
                <p class="mb-1"><strong>
                  @foreach($meal->ingredients as $ing)
                    {{ $ing->name }} ({{ $ing->pivot->quantity }} g)@if(!$loop->last), @endif
                  @endforeach
                </strong></p>
                <p class="text-muted mb-1">
                  {{ \Carbon\Carbon::parse($meal->date)->format('d/m/Y') }}
                  — {{ \Carbon\Carbon::parse($meal->time)->format('H:i') }}
                </p>
                <p class="small mb-0">
                  Carbohidratos: {{ round($mealCarbs,1) }} g |
                  Proteínas: {{ round($mealProt,1) }} g |
                  Grasas: {{ round($mealFats,1) }} g |
                  Calorías: {{ round($mealCals,1) }} kcal
                </p>
              </div>
              <div class="d-flex">
                <a href="{{ route('admin.meals.edit', $meal) }}"
                   class="btn btn-link text-warning p-0 me-3" title="Editar">
                  <i class="ri-pencil-line fs-4"></i>
                </a>
                <form action="{{ route('admin.meals.destroy', $meal) }}"
                      method="POST"
                      onsubmit="return confirm('¿Eliminar esta comida?');">
                  @csrf @method('DELETE')
                  <input type="hidden" name="date" value="{{ $dates['today']->format('Y-m-d') }}">
                  <button type="submit" class="btn btn-link text-danger p-0" title="Eliminar">
                    <i class="ri-delete-bin-2-line fs-4"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  @empty
    <p class="text-muted">No hay comidas registradas.</p>
  @endforelse
</div>

@endsection

@push('scripts')
<script>
  const chart = echarts.init(document.getElementById('macronutrientesChart'));
  chart.setOption({
    color: ['#BFA2E0', '#FF97B1', '#5CD6D5'],
    series:[{
      type:'pie',
      radius:['40%','70%'],
      label:{ formatter:'{b}: {c} g' },
      data:[
        { value: {{$carbohydrates}}, name:'Carbohidratos' },
        { value: {{$proteins}},      name:'Proteínas' },
        { value: {{$fats}},          name:'Grasas' }
      ]
    }]
  });
</script>
@endpush
