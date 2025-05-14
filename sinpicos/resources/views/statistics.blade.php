@extends('layouts.app')

@section('content')
<div class="container py-4 text-center">
  <h1 class="h3 fw-bold mb-3">Aquí están tus estadísticas, {{ Auth::user()->name }}</h1>
  <p class="text-muted mb-4">
    Resumen de {{ $startDate->format('d/m/Y') }} a {{ $endDate->format('d/m/Y') }}
  </p>
</div>

<div class="container py-4">
  {{-- RESUMEN MACROS + GLUCOSA --}}
  <div class="row row-cols-1 row-cols-md-5 g-4 mb-5">
    {{-- Macros --}}
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

    {{-- Glucosa --}}
    <div class="col">
      <div class="card h-100 text-center border-danger">
        <div class="card-body">
          <h5 class="card-title text-danger">Glucosa</h5>
          <h2 class="fw-bold">{{ number_format($avgGlucose,1) }} <small>mg/dL</small></h2>
          <p class="mb-1">Mín {{ $minGlucose }} – Máx {{ $maxGlucose }}</p>
        </div>
      </div>
    </div>
  </div>

  {{-- GRÁFICAS PRINCIPALES --}}
  <div class="row gy-4 mb-5">
    {{-- Line chart de macros --}}
    <div class="col-12 col-lg-6">
      <div class="card h-100">
        <div class="card-header">Macros últimos 7 días</div>
        <div class="card-body" style="height:300px;">
          <div id="lineMacros" class="w-100 h-100"></div>
        </div>
      </div>
    </div>

    {{-- Pie chart de macros --}}
    <div class="col-12 col-lg-6">
      <div class="card h-100">
        <div class="card-header">Distribución % de macros</div>
        <div class="card-body" style="height:300px;">
          <div id="pieMacros" class="w-100 h-100"></div>
        </div>
      </div>
    </div>

    {{-- Bar chart de calorías --}}
    <div class="col-12 col-lg-6">
      <div class="card h-100">
        <div class="card-header">Calorías diarias</div>
        <div class="card-body" style="height:300px;">
          <div id="barCalories" class="w-100 h-100"></div>
        </div>
      </div>
    </div>

    {{-- Línea de glucosa --}}
    <div class="col-12 col-lg-6">
      <div class="card h-100 border-danger">
        <div class="card-header text-danger">Glucosa diaria</div>
        <div class="card-body" style="height:300px;">
          <div id="lineGlucose" class="w-100 h-100"></div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Etiquetas DD/MM/YYYY
  const labels = @json($labels).map(d => {
    const [y,m,day] = d.split('-');
    return `${day}/${m}/${y}`;
  });

  // Datos
  const carbs    = @json($carbs);
  const proteins = @json($proteins);
  const fats     = @json($fats);
  const calories = @json($calories);
  const glucoseData = @json($glucoseValues);

  // Paleta pastel: rosa, lila, turquesa
  const pastelPalette = ['#BFA2E0', '#FF97B1', '#5CD6D5'];

  // 1) Line chart macros
  echarts.init(document.getElementById('lineMacros')).setOption({
    color: pastelPalette,
    tooltip: { trigger:'axis' },
    legend: { data:['Carbohidratos','Proteínas','Grasas'] },
    xAxis:  { type:'category', data: labels },
    yAxis:  { type:'value' },
    series: [
      { name:'Carbohidratos', type:'line', data: carbs },
      { name:'Proteínas',      type:'line', data: proteins },
      { name:'Grasas',         type:'line', data: fats }
    ]
  });

  // 2) Pie chart macros %
  echarts.init(document.getElementById('pieMacros')).setOption({
    color: pastelPalette,
    tooltip: { trigger:'item' },
    legend: { orient:'vertical', left:'left', data:['Carbohidratos','Proteínas','Grasas'] },
    series:[{
      type:'pie', radius:'60%',
      label:{ formatter:'{b}: {d}%' },
      data:[
        { value: carbs.reduce((a,b)=>a+b,0),    name:'Carbohidratos' },
        { value: proteins.reduce((a,b)=>a+b,0), name:'Proteínas' },
        { value: fats.reduce((a,b)=>a+b,0),     name:'Grasas' }
      ]
    }]
  });

  // 3) Bar chart calorías con naranja menos intenso
  echarts.init(document.getElementById('barCalories')).setOption({
    tooltip:{}, legend:{ data:['Calorías'] },
    xAxis:{ type:'category', data: labels },
    yAxis:{ type:'value' },
    series:[{
      name:'Calorías',
      type:'bar',
      data: calories,
      itemStyle:{ color: '#E67E22' }
    }]
  });

  // 4) Line chart glucosa
  echarts.init(document.getElementById('lineGlucose')).setOption({
    tooltip:{ trigger:'axis' },
    xAxis:{ type:'category', data: labels },
    yAxis:{ type:'value', name:'mg/dL' },
    series:[{
      data: glucoseData,
      type:'line',
      smooth:true,
      lineStyle:{ color:'#c0392b' },
      areaStyle:{ color:'rgba(192,57,43,0.2)' }
    }]
  });
</script>
@endpush
