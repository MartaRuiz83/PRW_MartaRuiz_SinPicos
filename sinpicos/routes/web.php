<?php

use App\Models\Tip;
use App\Http\Middleware\ControlAdmin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\GlucosaController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\PublicRecomendationController;
use App\Http\Controllers\RecomendationController as PublicRecs;
use App\Http\Controllers\Admin\RecomendationController as AdminRecs;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí definimos todas las rutas de la aplicación.
|
*/

// 1) Landing pública antes de login
Route::view('/', 'welcome')
     ->name('welcome');

// 2) Sección pública de recomendaciones
Route::get('/recomendaciones', [PublicRecomendationController::class, 'index'])
     ->name('recomendaciones');

// 3) Fortify/Jetstream (login, registro, etc.)
//    Alias “dashboard” para no romper enlaces hardcodeados
Route::middleware(['auth','verified'])
     ->get('/dashboard', fn() => redirect()->route('admin.dashboard'))
     ->name('dashboard');

// 4) RUTAS DEL FRONTEND (solo usuarios autenticados)
Route::middleware(['auth', 'verified'])->group(function () {
    // Home / comidas filtradas por fecha
    Route::get('/home/{date?}', [HomeController::class, 'index'])
         ->name('home');

    // CRUD comidas (frontend)
    Route::get('/meals/create', [MealController::class, 'create'])
         ->name('meals.create');
    Route::post('/meals',       [MealController::class, 'store'])
         ->name('meals.store');

    // Estadísticas
    Route::get('/statistics',   [StatisticsController::class, 'index'])
         ->name('statistics');

    // ——— RUTAS DE GLUCOSA ———
    // Primero las rutas estáticas / acciones
    Route::get('/glucosa/create',          [GlucosaController::class, 'create'])
         ->name('glucosa.create');
    Route::post('/glucosa',                [GlucosaController::class, 'store'])
         ->name('glucosa.store');
    Route::get('/glucosa/{glucosa}/edit',  [GlucosaController::class, 'edit'])
         ->name('glucosa.edit');
    Route::put('/glucosa/{glucosa}',       [GlucosaController::class, 'update'])
         ->name('glucosa.update');
    Route::delete('/glucosa/{glucosa}',    [GlucosaController::class, 'destroy'])
         ->name('glucosa.destroy');

    // Después la ruta de índice con fecha opcional (YYYY-MM-DD)
    Route::get('/glucosa/{date?}', [GlucosaController::class, 'index'])
         ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
         ->name('glucosa.index');
});

// 5) RUTAS DEL PANEL DE ADMIN (AdminLTE)
//    Prefijo /admin, nombre admin.*
Route::middleware(['auth', 'verified', ControlAdmin::class])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
    // 5.1) Dashboard AdminLTE
    Route::get('/dashboard', fn() => view('admin.dashboard'))
         ->name('dashboard');

    // 5.2) CRUD Recomendaciones
    Route::resource('recomendations', AdminRecs::class);

    // 5.3) CRUD Usuarios
    Route::resource('users', UserController::class);

    // 5.4) CRUD Ingredientes
    Route::resource('ingredients', IngredientController::class);

    // 5.5) CRUD Comidas en admin (sin create/store, ya están en frontend)
    Route::resource('meals', MealController::class)
         ->except(['create', 'store']);

    // 5.6) Marcar tips como vistos
    Route::post('/tips/showed/{tip}', function (Tip $tip) {
        $tip->showed = true;
        $tip->save();
        return redirect()->route('home', ['date' => now()->format('Y-m-d')]);
    })->name('tips.showed');
});
