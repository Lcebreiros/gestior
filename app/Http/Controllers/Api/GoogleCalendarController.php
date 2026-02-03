<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GoogleCalendarService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleCalendarController extends Controller
{
    protected GoogleCalendarService $googleCalendar;

    public function __construct(GoogleCalendarService $googleCalendar)
    {
        $this->googleCalendar = $googleCalendar;
    }

    /**
     * Get authorization URL
     */
    public function getAuthUrl(Request $request)
    {
        $authUrl = $this->googleCalendar->getAuthUrl();

        return response()->json([
            'success' => true,
            'data' => [
                'auth_url' => $authUrl,
            ],
        ]);
    }

    /**
     * Handle OAuth callback
     */
    public function handleCallback(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        try {
            $tokenData = $this->googleCalendar->handleCallback($request->code);

            $user = $request->user();
            $user->update([
                'google_access_token' => $tokenData['access_token'],
                'google_refresh_token' => $tokenData['refresh_token'],
                'google_token_expires_at' => $tokenData['expires_at'],
                'google_email' => $tokenData['email'],
                'google_calendar_sync_enabled' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cuenta de Google Calendar conectada exitosamente',
                'data' => [
                    'email' => $tokenData['email'],
                    'sync_enabled' => true,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al conectar con Google Calendar: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Disconnect Google Calendar
     */
    public function disconnect(Request $request)
    {
        $this->googleCalendar->forUser($request->user())->disconnect();

        return response()->json([
            'success' => true,
            'message' => 'Desconectado de Google Calendar exitosamente',
        ]);
    }

    /**
     * Toggle sync enabled/disabled
     */
    public function toggleSync(Request $request)
    {
        $request->validate([
            'sync_enabled' => 'required|boolean',
        ]);

        $user = $request->user();
        $user->update(['google_calendar_sync_enabled' => $request->sync_enabled]);

        $message = $request->sync_enabled
            ? 'Sincronización activada'
            : 'Sincronización desactivada';

        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => [
                'sync_enabled' => $request->sync_enabled,
            ],
        ]);
    }

    /**
     * Get connection status
     */
    public function status(Request $request)
    {
        $user = $request->user();
        $isConnected = $this->googleCalendar->forUser($user)->isConnected();

        return response()->json([
            'success' => true,
            'data' => [
                'connected' => $isConnected,
                'email' => $user->google_email,
                'sync_enabled' => $user->google_calendar_sync_enabled,
            ],
        ]);
    }

    /**
     * Authenticate with Google token directly (for mobile apps)
     * Este método es para apps móviles que usan google_sign_in package
     */
    public function authWithToken(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
            'email' => 'required|email',
            'id_token' => 'nullable|string',
        ]);

        try {
            $user = $request->user();

            // Almacenar el token directamente
            // En producción, podrías verificar el id_token con Google para más seguridad
            $user->update([
                'google_access_token' => [
                    'access_token' => $request->access_token,
                    'created' => time(),
                ],
                'google_email' => $request->email,
                'google_calendar_sync_enabled' => true,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cuenta de Google Calendar conectada exitosamente',
                'data' => [
                    'email' => $request->email,
                    'sync_enabled' => true,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al conectar con Google Calendar: ' . $e->getMessage(),
            ], 500);
        }
    }
}
