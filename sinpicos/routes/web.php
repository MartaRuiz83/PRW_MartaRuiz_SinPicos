<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\IngredientController;
use App\Http\Controllers\Admin\RecomendationController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Aquí registramos las rutas web de tu aplicación.
|
*/

// 1) La raíz redirige al login
Route::get('/', function () {
    return redirect()->route('login');
});

// 2) Ruta “global” Dashboard (sin prefijo admin), accesible con route('dashboard')
Route::middleware(['auth', 'verified'])
     ->get('/dashboard', function () {
         return view('admin.dashboard');
     })
     ->name('dashboard');

// 3) Todas las rutas /admin/* con middleware auth+verified y nombre admin.*
Route::middleware(['auth', 'verified'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

    // 3.1) Dashboard dentro de /admin/dashboard
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // 3.2) Home dentro de /admin/home
    Route::get('/home', function () {
        return view('home');
    })->name('home');

    // 3.3) CRUD Usuarios
    Route::resource('users', UserController::class);

    // 3.4) CRUD Ingredientes
    Route::resource('ingredients', IngredientController::class);

    // 3.5) CRUD Recomendaciones
    Route::resource('recomendations', RecomendationController::class);
});
