{{-- resources/views/home.blade.php --}}
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SinPicos - Control Nutricional para Diabéticos</title>
    <script src="https://cdn.tailwindcss.com/3.4.16"></script>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/5.5.0/echarts.min.js"></script>
</head>
<body class="bg-gray-50">
    @php use Carbon\Carbon; @endphp

    {{-- Navegación superior --}}
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('admin.home') }}" class="font-['Pacifico'] text-2xl text-primary">SinPicos</a>
                    <div class="hidden md:flex md:ml-10 space-x-8">
                        <a href="{{ route('admin.dashboard') }}" class="text-gray-900 hover:text-primary px-3 py-2 text-sm font-medium">Dashboard</a>
                        <a href="{{ route('meals.create') }}" class="text-gray-500 hover:text-primary px-3 py-2 text-sm font-medium">Registrar Comida</a>
                        <a href="#" class="text-gray-500 hover:text-primary px-3 py-2 text-sm font-medium">Estadísticas</a>
                        <a href="#" class="text-gray-500 hover:text-primary px-3 py-2 text-sm font-medium">Sugerencias</a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    @auth
                        <div class="relative">
                            <button id="userMenuBtn" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                    <span class="text-sm font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 2)) }}</span>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                            </button>
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                        <script>
                            const btn = document.getElementById('userMenuBtn'),
                                  menu = document.getElementById('userMenu');
                            btn.addEventListener('click', () => menu.classList.toggle('hidden'));
                            document.addEventListener('click', e => {
                                if (!btn.contains(e.target) && !menu.contains(e.target))
                                    menu.classList.add('hidden');
                            });
                        </script>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-primary">Entrar</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

            {{-- Resumen del Día --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="col-span-2 bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-semibold text-gray-900">Resumen del Día</h2>
                        <div class="text-sm text-gray-500">
                            {{ now()->locale('es')->isoFormat('D [de] MMMM, YYYY') }}
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="p-4 bg-blue-50 rounded-lg">
                            <div class="text-sm font-medium text-gray-500 mb-1">Carbohidratos</div>
                            <div class="text-2xl font-semibold text-gray-900">145g</div>
                            <div class="text-sm text-blue-600">de 180g objetivo</div>
                        </div>
                        <div class="p-4 bg-green-50 rounded-lg">
                            <div class="text-sm font-medium text-gray-500 mb-1">Proteínas</div>
                            <div class="text-2xl font-semibold text-gray-900">65g</div>
                            <div class="text-sm text-green-600">de 90g objetivo</div>
                        </div>
                        <div class="p-4 bg-yellow-50 rounded-lg">
                            <div class="text-sm font-medium text-gray-500 mb-1">Grasas</div>
                            <div class="text-2xl font-semibold text-gray-900">48g</div>
                            <div class="text-sm text-yellow-600">de 60g objetivo</div>
                        </div>
                    </div>
                    <div id="macronutrientesChart" class="h-64"></div>
                </div>

                {{-- Próxima Comida + Recordatorios --}}
                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Próxima Comida</h3>
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <i class="ri-restaurant-line text-2xl text-primary"></i>
                            <div>
                                <div class="font-medium text-gray-900">Almuerzo</div>
                                <div class="text-sm text-gray-500">13:00 - Ensalada mediterránea</div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Recordatorios</h3>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 p-3 bg-yellow-50 rounded-lg">
                                <i class="ri-timer-line text-yellow-600"></i>
                                <div class="text-sm text-yellow-800">Medición de glucosa en 30 min</div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                <i class="ri-calendar-check-line text-blue-600"></i>
                                <div class="text-sm text-blue-800">Consulta médica mañana 10:00</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Registro de Comidas --}}
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-gray-900">Registro de Comidas</h2>
                    <a href="{{ route('meals.create') }}"
                       class="flex items-center space-x-2 bg-primary text-white px-4 py-2 rounded-button">
                        <i class="ri-add-line"></i><span>Añadir Comida</span>
                    </a>
                </div>

                <div class="space-y-4">
                    @forelse($meals as $meal)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <i class="ri-restaurant-2-line text-2xl text-gray-600"></i>
                                <div>
                                    {{-- Ingredientes en lugar de descripción --}}
                                    <div class="font-medium text-gray-900">
                                        @foreach($meal->ingredients as $ing)
                                            {{ $ing->name }} ({{ $ing->pivot->quantity }} g)
                                            @if(!$loop->last), @endif
                                        @endforeach
                                    </div>

                                    {{-- Fecha y hora --}}
                                    <div class="text-sm text-gray-500">
                                        {{ Carbon::parse($meal->date)->format('d/m/Y') }}
                                        –
                                        {{ Carbon::parse($meal->time)->format('H:i') }}
                                    </div>

                                    {{-- Totales nutricionales --}}
                                    @php
                                        $totalCarbs    = 0;
                                        $totalProteins = 0;
                                        $totalFats     = 0;
                                        foreach($meal->ingredients as $ing) {
                                            $q = $ing->pivot->quantity;
                                            $totalCarbs    += ($ing->carbohydrates_per_100g ?? 0) * $q / 100;
                                            $totalProteins += ($ing->proteins_per_100g      ?? 0) * $q / 100;
                                            $totalFats     += ($ing->fats_per_100g          ?? 0) * $q / 100;
                                        }
                                    @endphp
                                    <div class="text-xs text-gray-600 mt-1">
                                        Carbos: {{ round($totalCarbs,1) }} g |
                                        Prot: {{ round($totalProteins,1) }} g |
                                        Grasas: {{ round($totalFats,1) }} g
                                    </div>
                                </div>
                            </div>

                            {{-- Botones ver / editar / eliminar --}}
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.meals.show', $meal) }}"
                                   class="p-2 text-blue-500 hover:text-blue-700" title="Ver detalle">
                                    <i class="ri-eye-line text-xl"></i>
                                </a>
                                <a href="{{ route('admin.meals.edit', $meal) }}"
                                   class="p-2 text-yellow-500 hover:text-yellow-700" title="Editar">
                                    <i class="ri-pencil-line text-xl"></i>
                                </a>
                                <form action="{{ route('admin.meals.destroy', $meal) }}"
                                      method="POST" onsubmit="return confirm('¿Eliminar esta comida?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:text-red-700" title="Eliminar">
                                        <i class="ri-delete-bin-line text-xl"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No hay comidas registradas.</p>
                    @endforelse
                </div>

                {{-- Paginación si la hubiera --}}
                @if(method_exists($meals, 'links'))
                    <div class="mt-6">{{ $meals->links() }}</div>
                @endif
            </div>
        </div>
    </main>

    {{-- Chart de macros --}}
    <script>
        const chart = echarts.init(document.getElementById('macronutrientesChart'));
        chart.setOption({
            animation: false,
            tooltip:{ trigger:'item', backgroundColor:'#fff', borderColor:'#eee', borderWidth:1, textStyle:{color:'#1f2937'} },
            series:[{
                name:'Macros', type:'pie', radius:['40%','70%'], itemStyle:{borderRadius:8},
                label:{show:true,position:'outside',formatter:'{b}: {c}g'},
                data:[
                    {value:145, name:'Carbohidratos'},
                    {value:65,  name:'Proteínas'},
                    {value:48,  name:'Grasas'}
                ]
            }]
        });
    </script>
</body>
</html>
