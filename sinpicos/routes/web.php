<?php

use App\Models\Tip;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MealController;
use App\Http\Controllers\Admin\UserController;
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

// ——————————————————————————————————————————————————————————
// 0) Alias “dashboard” para no romper enlaces hard-codeados
// ——————————————————————————————————————————————————————————
Route::middleware(['auth','verified'])
     ->get('/dashboard', fn() => redirect()->route('admin.dashboard'))
     ->name('dashboard');

// 1) Al entrar en “/” te lleva al login (Fortify/Jetstream lo maneja).
Route::get('/', fn() => redirect()->route('login'));

// 2) RUTAS DEL FRONTEND (sólo para usuarios logueados)
//    - /home         → listado público de comidas
//    - /meals/create → formulario para crear comida
//    - POST /meals   → guardar en BD
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/home/{date?}',           [HomeController::class, 'index'])
         ->name('home');

    Route::get('/meals/create',   [MealController::class, 'create'])
         ->name('meals.create');

    Route::post('/meals',         [MealController::class, 'store'])
         ->name('meals.store');
});

// 3) RUTAS DEL PANEL DE ADMIN (AdminLTE)
//    Todas estas usan prefijo /admin y nombre admin.*
Route::middleware(['auth', 'verified'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {



    // 3.5) CRUD Recomendaciones
    Route::resource('recomendations', RecomendationController::class);

    // 3.1) Dashboard principal de AdminLTE
    Route::get('/dashboard', fn() => view('admin.dashboard'))
         ->name('dashboard');

    // 3.3) CRUD de Usuarios
    Route::resource('users', UserController::class);

    // 3.4) CRUD de Ingredientes
    Route::resource('ingredients', IngredientController::class);
    Route::resource('meals', MealController::class)
         ->except(['create', 'store']);

     // Rutas Tip Showed
    Route::post('/tips/showed/{tip}', function (Tip $tip) {
        $tip->showed = true;
        $tip->save();
        return redirect()->route('home');

     })->name('tips.showed');

 });  

