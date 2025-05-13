<?php

use App\Models\Tip;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MealController;
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
//    - /home/{date?}   → listado público de comidas (filtrado por fecha)
//    - /meals/create   → formulario de creación
//    - POST /meals     → guardar en BD
//    - /statistics     → estadísticas de usuario
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home/{date?}',      [HomeController::class, 'index'])
         ->name('home');

    Route::get('/meals/create',      [MealController::class, 'create'])
         ->name('meals.create');

    Route::post('/meals',            [MealController::class, 'store'])
         ->name('meals.store');

    Route::get('/statistics',        [StatisticsController::class, 'index'])
         ->name('statistics');
});

// 5) RUTAS DEL PANEL DE ADMIN (AdminLTE)
//    Prefijo /admin, nombre admin.*
Route::middleware(['auth', 'verified'])
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

    // 5.5) CRUD Comidas en admin (sin create/store, que están en frontend)
    Route::resource('meals', MealController::class)
         ->except(['create', 'store']);

    // 5.6) Marcar tips como vistos
    Route::post('/tips/showed/{tip}', function (Tip $tip) {
        $tip->showed = true;
        $tip->save();
        return redirect()->route('home', ['date' => now()->format('Y-m-d')]);
    })->name('tips.showed');
});
