<?php

use App\Models\Tip;
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
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\ControlAdmin;

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
    Route::get('/glucosa/{date?}', [GlucosaController::class, 'index'])
         ->where('date', '[0-9]{4}-[0-9]{2}-[0-9]{2}')
         ->name('glucosa.index');

    // ✅ Rutas para perfil de usuario
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])
         ->name('perfil.edit');
    Route::put('/perfil/editar', [ProfileController::class, 'update'])
         ->name('perfil.update');

    // ✅ Ruta para marcar tip como visto (disponible para cualquier usuario)
    Route::post('/tips/showed/{tip}', function (Tip $tip) {
        $tip->showed = true;
        $tip->save();
        return redirect()->route('home', ['date' => now()->format('Y-m-d')]);
    })->name('tips.showed');
});

// 5) RUTAS DEL PANEL DE ADMIN (AdminLTE)
Route::middleware(['auth', 'verified', ControlAdmin::class])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))
         ->name('dashboard');

    Route::resource('recomendations', AdminRecs::class);
    Route::resource('users', UserController::class);
    Route::resource('ingredients', IngredientController::class);
    Route::resource('meals', MealController::class)->except(['create', 'store']);
});
