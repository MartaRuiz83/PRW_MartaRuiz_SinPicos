<?php

// routes/web.php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Jetstream/Fortify (login, register…) ya vienen automáticamente

Route::middleware(['auth','verified'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function(){

    // Dashboard
    Route::get('/dashboard', function(){
        return view('admin.dashboard');
    })->name('dashboard');

    // Home dentro de Admin (solo tras login)
    Route::get('/home', function(){
        return view('home');
    })->name('home');

    // Recursos
    Route::resource('users',       App\Http\Controllers\Admin\UserController::class);
    Route::resource('ingredients', App\Http\Controllers\Admin\IngredientController::class);
});
