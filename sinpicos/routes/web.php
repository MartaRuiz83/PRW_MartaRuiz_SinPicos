<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])
     ->prefix('admin')
     ->name('admin.')                  // ← Prefijo para nombres de ruta
     ->group(function () { 
         
    // Dashboard ya recibe el nombre 'admin.dashboard'
    Route::get('/dashboard', function () { 
        return view('admin.dashboard'); 
    })->name('dashboard');             // Será 'admin.dashboard'

    // Rutas Usuarios
    Route::resource('users', UserController::class);

    // Rutas Ingredientes
    Route::resource('ingredients', App\Http\Controllers\Admin\IngredientController::class);


});
