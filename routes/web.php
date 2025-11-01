<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;

// Rutas públicas
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Página de servicios (pública)
Route::view('/servicios', 'services')->name('services');

// Página de planes (pública)
Route::view('/planes', 'plans')->name('plans');
// Alias en inglés para compatibilidad con enlaces existentes
Route::view('/pricing', 'plans')->name('pricing');

// Página de contacto (pública)
Route::view('/contacto', 'contact')->name('contact');

// Rutas de suscripción (requiere autenticación y email verificado)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Selección de plan
    Route::get('/subscription/plans', [SubscriptionController::class, 'showPlans'])
        ->name('subscription.plans');
    Route::post('/subscription/select', [SubscriptionController::class, 'selectPlan'])
        ->name('subscription.select');

    // Activación con código
    Route::get('/subscription/activate', [SubscriptionController::class, 'showActivation'])
        ->name('subscription.activate');
    Route::post('/subscription/activate', [SubscriptionController::class, 'activate'])
        ->name('subscription.activate.submit');
});

// Rutas autenticadas que requieren suscripción activa
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'subscription',
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
