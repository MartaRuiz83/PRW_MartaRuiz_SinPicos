<?php

use App\Models\Tip;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\RecomendationController;

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

// 2) Fortify/Jetstream login, registro, etc (ya incluidas en service provider)
//    Alias “dashboard” para no romper enlaces hard-codeados
Route::middleware(['auth','verified'])
     ->get('/dashboard', fn() => redirect()->route('admin.dashboard'))
     ->name('dashboard');

// 3) RUTAS DEL FRONTEND (sólo para usuarios logueados)
//    - /home/{date?}   → listado público de comidas filtrado por fecha
//    - /meals/create   → formulario para crear comida
//    - POST /meals     → guardar en BD
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home/{date?}', [HomeController::class, 'index'])
         ->name('home');

    Route::get('/meals/create', [MealController::class, 'create'])
         ->name('meals.create');

    Route::post('/meals', [MealController::class, 'store'])
         ->name('meals.store');

    // Estadísticas para usuarios logueados
    Route::get('/statistics', [StatisticsController::class, 'index'])
         ->name('statistics');
});

// 4) RUTAS DEL PANEL DE ADMIN (AdminLTE)
//    Todas estas usan prefijo /admin y nombre admin.*
Route::middleware(['auth', 'verified'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    // 4.1) Dashboard principal de AdminLTE
    Route::get('/dashboard', fn() => view('admin.dashboard'))
         ->name('dashboard');

    // 4.2) CRUD Recomendaciones
    Route::resource('recomendations', RecomendationController::class);

    // 4.3) CRUD Usuarios
    Route::resource('users', UserController::class);

    // 4.4) CRUD Ingredientes
    Route::resource('ingredients', IngredientController::class);

    // 4.5) CRUD Comidas en admin (sin create/store, que están en frontend)
    Route::resource('meals', MealController::class)
         ->except(['create', 'store']);

    // 4.6) Marcar tips como vistos
    Route::post('/tips/showed/{tip}', function (Tip $tip) {
        $tip->showed = true;
        $tip->save();
        return redirect()->route('home', ['date' => now()->format('Y-m-d')]);
    })->name('tips.showed');

});
