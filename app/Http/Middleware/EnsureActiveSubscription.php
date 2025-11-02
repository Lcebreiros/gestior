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

        // Si no ha seleccionado un plan, redirigir a selección de plan
        if (is_null($user->subscription_level)) {
            return redirect()->route('subscription.plans')
                ->with('info', 'Por favor, selecciona un plan para continuar');
        }

        // Si seleccionó plan pero no está activo, redirigir a activación
        if (!$user->is_active) {
            return redirect()->route('subscription.activate')
                ->with('info', 'Por favor, ingresa tu código de invitación para activar tu cuenta');
        }

        // Usuario tiene suscripción activa, continuar
        return $next($request);
    }
}
