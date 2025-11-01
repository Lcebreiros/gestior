<?php

namespace App\Http\Middleware;

use App\Services\SubscriptionService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveSubscription
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Si no hay usuario autenticado, Laravel ya lo redirigirá con el middleware 'auth'
        if (!$user) {
            return $next($request);
        }

        // Verificar si el email está verificado
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }

        // Si no ha seleccionado un plan o no está activo, redirigir a planes
        if (is_null($user->subscription_level) || !$user->is_active) {
            return redirect()->route('plans')
                ->with('info', 'Por favor, selecciona un plan e ingresa tu código de invitación');
        }

        // Usuario tiene suscripción activa, continuar
        return $next($request);
    }
}
