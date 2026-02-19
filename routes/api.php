<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\GoogleCalendarController;
use App\Http\Controllers\Api\HealthReportController;
use App\Http\Controllers\Api\InsightsController;
use App\Http\Controllers\Api\NotificationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Rutas del calendario
Route::middleware('auth:sanctum')->prefix('calendar')->group(function () {
    // Resumen y estadísticas
    Route::get('/summary', [CalendarController::class, 'getSummary']);

    // Obtener eventos
    Route::get('/month/{year}/{month}', [CalendarController::class, 'getMonth']);
    Route::get('/range', [CalendarController::class, 'getRange']);
    Route::get('/upcoming', [CalendarController::class, 'getUpcoming']);
    Route::get('/overdue', [CalendarController::class, 'getOverdue']);
    Route::get('/type/{type}', [CalendarController::class, 'getByType']);

    // Gestionar eventos
    Route::post('/events', [CalendarController::class, 'store']);
    Route::put('/events/{id}', [CalendarController::class, 'update']);
    Route::delete('/events/{id}', [CalendarController::class, 'destroy']);
    Route::post('/events/{id}/complete', [CalendarController::class, 'markAsCompleted']);
});

// Rutas de Google Calendar
Route::middleware('auth:sanctum')->prefix('google-calendar')->group(function () {
    Route::get('/auth-url', [GoogleCalendarController::class, 'getAuthUrl']);
    Route::post('/callback', [GoogleCalendarController::class, 'handleCallback']);
    Route::post('/auth-with-token', [GoogleCalendarController::class, 'authWithToken']); // Para apps móviles
    Route::post('/disconnect', [GoogleCalendarController::class, 'disconnect']);
    Route::post('/toggle-sync', [GoogleCalendarController::class, 'toggleSync']);
    Route::get('/status', [GoogleCalendarController::class, 'status']);
});

// Rutas de insights (Nexum)
Route::middleware('auth:sanctum')->prefix('insights')->group(function () {
    Route::get('/', [InsightsController::class, 'index']);
    Route::post('/generate', [InsightsController::class, 'generate']);
    Route::get('/stats', [InsightsController::class, 'stats']);
    Route::patch('/{id}/dismiss', [InsightsController::class, 'dismiss']);
    Route::get('/health-report', [HealthReportController::class, 'show']);
});

// Rutas de notificaciones
Route::middleware('auth:sanctum')->prefix('notifications')->group(function () {
    Route::get('/', [NotificationController::class, 'index']);
    Route::get('/unread', [NotificationController::class, 'unread']);
    Route::get('/count', [NotificationController::class, 'count']);
    Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::delete('/{id}', [NotificationController::class, 'destroy']);
    Route::post('/fcm-token', [NotificationController::class, 'updateFcmToken']);
});
