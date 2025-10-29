<?php

use Illuminate\Support\Facades\Route;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Página de planes (pública)
Route::view('/planes', 'plans')->name('plans');
// Alias en inglés para compatibilidad con enlaces existentes
Route::view('/pricing', 'plans')->name('pricing');

// Rutas autenticadas
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Si Jetstream no registró la ruta 'profile.show', definir fallback
    if (!Route::has('profile.show')) {
        Route::get('/user/profile', function () {
            return view('profile.show');
        })->name('profile.show');
    }
});
