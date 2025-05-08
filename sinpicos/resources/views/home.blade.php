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
    <style>
        :where([class^="ri-"])::before { content: "\f3c2"; }
        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4F46E5',
                        secondary: '#10B981'
                    },
                    borderRadius: {
                        'none': '0px',
                        'sm': '4px',
                        DEFAULT: '8px',
                        'md': '12px',
                        'lg': '16px',
                        'xl': '20px',
                        '2xl': '24px',
                        '3xl': '32px',
                        'full': '9999px',
                        'button': '8px'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <nav class="fixed top-0 left-0 right-0 bg-white shadow-sm z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="#" class="font-['Pacifico'] text-2xl text-primary">SinPicos</a>
                    <div class="hidden md:flex md:ml-10 space-x-8">
                        <!-- Enlace al Dashboard de AdminLTE -->
                        <a href="{{ route('admin.dashboard') }}"
                           class="text-gray-900 hover:text-primary px-3 py-2 text-sm font-medium">
                            Dashboard
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary px-3 py-2 text-sm font-medium">
                            Registro de Comidas
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary px-3 py-2 text-sm font-medium">
                            Estadísticas
                        </a>
                        <a href="#" class="text-gray-500 hover:text-primary px-3 py-2 text-sm font-medium">
                            Sugerencias
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <i class="ri-notification-3-line text-gray-500"></i>
                        </div>
                    </button>

                    @auth
                        <div class="relative">
                            <button id="userMenuBtn" class="flex items-center space-x-2 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center">
                                    <span class="text-sm font-medium">
                                        {{ strtoupper(substr(auth()->user()->name,0,2)) }}
                                    </span>
                                </div>
                                <span class="text-sm font-medium text-gray-700">
                                    {{ auth()->user()->name }}
                                </span>
                            </button>
                            <div id="userMenu" class="hidden absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Cerrar sesión
                                    </button>
                                </form>
                            </div>
                        </div>
                        <script>
                            const btn = document.getElementById('userMenuBtn');
                            const menu = document.getElementById('userMenu');
                            btn.addEventListener('click', ()=> menu.classList.toggle('hidden'));
                            document.addEventListener('click', e=>{
                                if(!btn.contains(e.target) && !menu.contains(e.target)){
                                    menu.classList.add('hidden');
                                }
                            });
                        </script>
                    @else
                        <a href="{{ route('login') }}"
                           class="text-sm text-gray-700 hover:text-primary">Entrar</a>
                    @endauth

                </div>
            </div>
        </div>
    </nav>

    <main class="pt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-12 gap-6">
                <div class="col-span-12 lg:col-span-8">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Resumen del Día</h2>
                            <div class="text-sm text-gray-500">
                                {{ now()->format('d \d\e F, Y') }}
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
                        <div class="h-80" id="macronutrientesChart"></div>
                    </div>
                </div>

                <div class="col-span-12 lg:col-span-4">
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Próxima Comida</h3>
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                            <div class="w-12 h-12 flex items-center justify-center">
                                <i class="ri-restaurant-line text-2xl text-primary"></i>
                            </div>
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
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <i class="ri-timer-line text-yellow-600"></i>
                                </div>
                                <div class="text-sm text-yellow-800">Medición de glucosa en 30 min</div>
                            </div>
                            <div class="flex items-center space-x-3 p-3 bg-blue-50 rounded-lg">
                                <div class="w-8 h-8 flex items-center justify-center">
                                    <i class="ri-calendar-check-line text-blue-600"></i>
                                </div>
                                <div class="text-sm text-blue-800">Consulta médica mañana 10:00</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-span-12">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-xl font-semibold text-gray-900">Registro de Comidas</h2>
                            <button class="flex items-center space-x-2 bg-primary text-white px-4 py-2 rounded-button">
                                <i class="ri-add-line"></i>
                                <span>Añadir Comida</span>
                            </button>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="ri-search-line text-gray-400"></i>
                            </div>
                            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg text-sm" placeholder="Buscar alimentos...">
                        </div>
                        <div class="mt-6">
                            <div class="flex items-center space-x-4 mb-4">
                                <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Desayuno</button>
                                <button class="px-4 py-2 text-sm font-medium text-primary bg-primary/10 rounded-full">Almuerzo</button>
                                <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Cena</button>
                                <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-full">Snacks</button>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 flex items-centerjustify-center">
                                            <i class="ri-restaurant-2-line text-2xl text-gray-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Ensalada de Quinoa</div>
                                            <div class="text-sm text-gray-500">250g - 320 kcal</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button class="p-2 text-gray-400 hover:text-gray-600">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="p-2 text-gray-400 hover:text-gray-600">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 flex items-center justify-center">
                                            <i class="ri-restaurant-2-line text-2xl text-gray-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Pechuga de Pollo</div>
                                            <div class="text-sm text-gray-500">180g - 265 kcal</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <button class="p-2 text-gray-400 hover:text-gray-600">
                                            <i class="ri-edit-line"></i>
                                        </button>
                                        <button class="p-2 text-gray-400 hover:text-gray-600">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const macronutrientesChart = echarts.init(document.getElementById('macronutrientesChart'));
        const option = {
            animation: false,
            tooltip: {
                trigger: 'item',
                backgroundColor: 'rgba(255, 255, 255, 0.9)',
                borderColor: '#eee',
                borderWidth: 1,
                textStyle: {
                    color: '#1f2937'
                }
            },
            series: [{
                name: 'Macronutrientes',
                type: 'pie',
                radius: ['40%', '70%'],
                itemStyle: { borderRadius: 8 },
                label: { show: true, position: 'outside', formatter: '{b}: {c}g' },
                data: [
                    { value: 145, name: 'Carbohidratos', itemStyle: { color: 'rgba(87,181,231,1)' } },
                    { value: 65, name: 'Proteínas',   itemStyle: { color: 'rgba(141,211,199,1)' } },
                    { value: 48, name: 'Grasas',      itemStyle: { color: 'rgba(251,191,114,1)' } }
                ]
            }]
        };
        macronutrientesChart.setOption(option);
    </script>
</body>
</html>
