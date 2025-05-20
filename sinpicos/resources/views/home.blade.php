@extends('layouts.app')

@section('content')

<style>
  .text-orange { color:  #FF5722 } /* Naranja intenso */
  .btn-primary {
    background-color: #7d3ced; /* Color actual */
    border-color: #7d3ced;
  }
  .btn-primary:hover {
    background-color: #5a1fa6; /* Púrpura oscuro */
    border-color: #5a1fa6;
  }
</style>

{{-- Cabecera de navegación de días --}}
<div class="container py-4 px-3 px-sm-4">
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

{{-- Consejos del día --}}
<div class="container py-4 px-3 px-sm-4">
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
            <form action="{{ route('admin.tips.showed', $tip) }}"
                  method="POST"
                  class="mt-3 text-end">
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
  // Rangos para colores
  $ranges = [
    'carbohydrates'=> ['min'=>130,'max'=>180],
    'proteins'     => ['min'=>75, 'max'=>100],
    'fats'         => ['min'=>50, 'max'=>70],
    'calories'     => ['min'=>1500,'max'=>2000],
  ];
  function getColorClass($v,$min,$max){
    if($v<$min) return 'text-success';
    if($v<$max) return 'text-orange';
    return 'text-danger';
  }
  // Agrupamos comidas y definimos orden
  $grouped = $meals->groupBy('meal_type');
  $order   = ['Desayuno','Almuerzo','Snack','Cena'];
@endphp

{{-- Resumen del Día (datos nutricionales) --}}
<div class="container px-3 px-sm-4 mb-4">
  <div class="row row-cols-1 row-cols-md-4 g-4">
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
</div>

{{-- Leyenda ultra-compacta --}}
<div class="d-flex justify-content-center gap-2 mb-3 small px-3 px-sm-4" style="font-size: .75rem;">
  <div class="d-flex align-items-center">
    <i class="ri-checkbox-blank-circle-fill text-success me-1" style="font-size: .75rem;"></i>
    <span>Verde: Aún tienes margen en el consumo</span>
  </div>
  <div class="d-flex align-items-center">
    <i class="ri-checkbox-blank-circle-fill text-orange me-1" style="font-size: .75rem;"></i>
    <span>Naranja: Precaución, mantente dentro del rango</span>
  </div>
  <div class="d-flex align-items-center">
    <i class="ri-checkbox-blank-circle-fill text-danger me-1" style="font-size: .75rem;"></i>
    <span>Rojo: Te has excedido en el consumo</span>
  </div>
</div>

{{-- Gráfico de macronutrientes --}}
<div class="container px-3 px-sm-4 mb-5">
  <div class="card">
    <div class="card-body" style="height:300px;">
      <div id="macronutrientesChart" class="w-100 h-100"></div>
    </div>
  </div>
</div>

{{-- Registro de Comidas agrupado por tipo --}}
<div class="container mb-5 px-3 px-sm-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h2 class="h5 mb-0">Registro de Comidas</h2>
    <a href="{{ route('meals.create') }}" class="btn btn-primary">
      <i class="ri-add-line"></i> Añadir Comida
    </a>
  </div>

  @foreach($order as $type)
    @if(isset($grouped[$type]) && $grouped[$type]->isNotEmpty())
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
          <h4 class="fw-bold mb-0"
              style="background: linear-gradient(90deg, #7d3ced, #c77dff);
                     -webkit-background-clip: text;
                     color: transparent;">
            {{ $type }}
          </h4>
        </div>

        @foreach($grouped[$type] as $meal)
          <div class="card mb-2">
            <div class="card-body d-flex justify-content-between align-items-start">
              <div>
                @if($meal->description)
                  <h5 class="mb-1">{{ $meal->description }}</h5>
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
                  Carbohidratos: {{ round($meal->ingredients->reduce(fn($c,$ing)=> $c + (($ing->carbohydrates ?? 0)*$ing->pivot->quantity/100),0),1) }} g |
                  Proteínas: {{ round($meal->ingredients->reduce(fn($c,$ing)=> $c + (($ing->proteins      ?? 0)*$ing->pivot->quantity/100),0),1) }} g |
                  Grasas:      {{ round($meal->ingredients->reduce(fn($c,$ing)=> $c + (($ing->fats          ?? 0)*$ing->pivot->quantity/100),0),1) }} g |
                  Calorías:    {{ round($meal->ingredients->reduce(fn($c,$ing)=> $c + (($ing->calories      ?? 0)*$ing->pivot->quantity/100),0),1) }} kcal
                </p>
              </div>
              <div class="d-flex">
                <a href="{{ route('admin.meals.edit', $meal) }}"
                   class="btn btn-link text-warning p-0 me-3" title="Editar">
                  <i class="ri-pencil-line fs-4"></i>
                </a>
                <form action="{{ route('admin.meals.destroy', $meal) }}"
                      method="POST"
                      class="delete-form">
                  @csrf @method('DELETE')
                  <input type="hidden" name="date"
                         value="{{ $dates['today']->format('Y-m-d') }}">
                  <button type="submit"
                          class="btn btn-link text-danger p-0"
                          title="Eliminar">
                    <i class="ri-delete-bin-2-line fs-4"></i>
                  </button>
                </form>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  @endforeach

</div>

@endsection

@push('scripts')
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <script>
    // Mostrar alerta de éxito si existe, excepto tras editar
    @if(session('success'))
      let msg = @json(session('success'));
      if (!msg.toLowerCase().includes('actualizada')) {
        Swal.fire({
          icon: 'success',
          title: '¡Éxito!',
          text: msg,
          confirmButtonText: 'Aceptar',
          confirmButtonColor: '#28a745'
        });
      }
    @endif

    // Confirmación para eliminación de comidas (botón rojo)
    document.querySelectorAll('.delete-form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
          title: '¿Eliminar esta comida?',
          text: "¡Esta acción no se puede deshacer!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, eliminar',
          cancelButtonText: 'Cancelar',
          confirmButtonColor: '#dc3545',
          cancelButtonColor:  '#6c757d'
        }).then(result => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  </script>

  <!-- ECharts: Macronutrientes -->
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
