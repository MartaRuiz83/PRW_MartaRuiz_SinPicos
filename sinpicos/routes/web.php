<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () { 
    Route::get('/dashboard', function () { 
        return view('admin.dashboard'); 
    })->name('admin.dashboard'); 
 }); 
