@extends('layouts.app')

@section('content')
<div class="container py-4">
  <h1 class="h3 fw-bold mb-3">Estadísticas personales</h1>
  <p class="text-muted mb-4">
    Resumen de {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}
  </p>

  {{-- Resumen acumulado --}}
  <div class="row row-cols-1 row-cols-md-4 g-4 mb-5">
    @foreach ([
      ['label'=>'Carbohidratos','value'=>$totalCarbs,'unit'=>'g'],
      ['label'=>'Proteínas','value'=>$totalProteins,'unit'=>'g'],
      ['label'=>'Grasas','value'=>$totalFats,'unit'=>'g'],
      ['label'=>'Calorías','value'=>$totalCalories,'unit'=>'kcal'],
    ] as $stat)
      <div class="col">
        <div class="card h-100 text-center">
          <div class="card-body">
            <h5 class="card-title">{{ $stat['label'] }}</h5>
            <h2 class="fw-bold">{{ $stat['value'] }} <small>{{ $stat['unit'] }}</small></h2>
          </div>
        </div>
      </div>
    @endforeach
  </div>

  {{-- Gráficas --}}
  <div class="row gy-4">
    {{-- Line chart de macros --}}
    <div class="col-12 col-lg-6">
      <div class="card h-100">
        <div class="card-header">Macros últimos 7 días</div>
        <div class="card-body" style="height:300px;">
          <div id="lineChart" class="w-100 h-100"></div>
        </div>
      </div>
    </div>

    {{-- Pie chart de distribución --}}
    <div class="col-12 col-lg-6">
      <div class="card h-100">
        <div class="card-header">Distribución % de macros</div>
        <div class="card-body" style="height:300px;">
          <div id="pieChart" class="w-100 h-100"></div>
        </div>
      </div>
    </div>

    {{-- Bar chart de calorías diarios --}}
    <div class="col-12">
      <div class="card">
        <div class="card-header">Calorías diarias</div>
        <div class="card-body" style="height:300px;">
          <div id="barChart" class="w-100 h-100"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Datos originales de fechas en formato YYYY-MM-DD
  const rawLabels = @json($labels);
  // Reformateo a DD/MM/YYYY para mostrar en eje X
  const labels = rawLabels.map(dateStr => {
    const parts = dateStr.split('-');
    return parts[2] + '/' + parts[1] + '/' + parts[0];
  });

  const carbs    = @json($carbs);
  const proteins = @json($proteins);
  const fats     = @json($fats);
  const calories = @json($calories);

  // 1) Line chart de macros
  const chart1 = echarts.init(document.getElementById('lineChart'));
  chart1.setOption({
    tooltip: { trigger: 'axis' },
    legend:  { data: ['Carbohidratos','Proteínas','Grasas'] },
    xAxis:   { type: 'category', data: labels },
    yAxis:   { type: 'value' },
    series: [
      { name:'Carbohidratos', type:'line', data: carbs },
      { name:'Proteínas',      type:'line', data: proteins },
      { name:'Grasas',         type:'line', data: fats }
    ]
  });

  // 2) Pie chart de porcentaje
  const chart2 = echarts.init(document.getElementById('pieChart'));
  chart2.setOption({
    tooltip: { trigger: 'item' },
    legend:  {
      orient: 'vertical',
      left: 'left',
      data: ['Carbohidratos','Proteínas','Grasas']
    },
    series: [{
      type: 'pie',
      radius: '60%',
      label: { formatter: '{b}: {d}%' },
      data: [
        { value: carbs.reduce((a,b)=>a+b,0),    name:'Carbohidratos' },
        { value: proteins.reduce((a,b)=>a+b,0), name:'Proteínas' },
        { value: fats.reduce((a,b)=>a+b,0),     name:'Grasas' }
      ]
    }]
  });

  // 3) Bar chart de calorías con color lila
  const chart3 = echarts.init(document.getElementById('barChart'));
  chart3.setOption({
    tooltip: {},
    legend:  { data: ['Calorías'] },
    xAxis:   { type: 'category', data: labels },
    yAxis:   { type: 'value' },
    series: [
      { name:'Calorías', type:'bar', data: calories, itemStyle: { color: '#7d3ced' } }
    ]
  });
</script>
@endpush