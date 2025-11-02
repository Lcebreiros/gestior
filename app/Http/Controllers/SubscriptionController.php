<?php

namespace App\Http\Controllers;

use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Muestra la página de selección de plan
     */
    public function showPlans()
    {
        $user = Auth::user();

        // Si ya tiene suscripción activa, redirigir al dashboard
        if ($this->subscriptionService->hasActiveSubscription($user)) {
            return redirect()->route('dashboard');
        }

        // Si ya seleccionó un plan, redirigir a activación
        if (!is_null($user->subscription_level)) {
            return redirect()->route('subscription.activate');
        }

        $plans = SubscriptionService::getAllPlans();

        return view('subscription.select-plan', compact('plans'));
    }

    /**
     * Guarda el plan seleccionado por el usuario
     */
    public function selectPlan(Request $request)
    {
        $request->validate([
            'plan' => ['required', Rule::in(array_keys(SubscriptionService::getAllPlans()))],
        ]);

        $user = Auth::user();

        // Si ya tiene suscripción activa, no puede cambiar
        if ($this->subscriptionService->hasActiveSubscription($user)) {
            return redirect()->route('dashboard')
                ->with('error', 'Ya tienes una suscripción activa');
        }

        // Asignar el plan
        $this->subscriptionService->assignPlan($user, $request->plan);

        // Redirigir a la página de activación
        return redirect()->route('subscription.activate')
            ->with('success', 'Plan seleccionado correctamente. Ahora ingresa tu código de invitación.');
    }

    /**
     * Muestra la página de activación con código
     */
    public function showActivation()
    {
        $user = Auth::user();

        // Si ya tiene suscripción activa, redirigir al dashboard
        if ($this->subscriptionService->hasActiveSubscription($user)) {
            return redirect()->route('dashboard');
        }

        // Si no ha seleccionado un plan, redirigir a selección
        if (is_null($user->subscription_level)) {
            return redirect()->route('subscription.plans');
        }

        $planInfo = SubscriptionService::getPlanInfo($user->subscription_level);

        return view('subscription.activate', compact('planInfo'));
    }

    /**
     * Procesa el código de invitación y activa la suscripción
     */
    public function activate(Request $request)
    {
        $request->validate([
            'invitation_code' => 'required|string|min:10',
            'plan' => ['required', Rule::in(array_keys(SubscriptionService::getAllPlans()))],
        ]);

        $user = Auth::user();

        // Verificar que no tenga suscripción activa
        if ($this->subscriptionService->hasActiveSubscription($user)) {
            return redirect()->route('dashboard')
                ->with('error', 'Ya tienes una suscripción activa');
        }

        // Asignar el plan si no lo tiene o es diferente
        if ($user->subscription_level !== $request->plan) {
            $this->subscriptionService->assignPlan($user, $request->plan);
            $user->refresh();
        }

        // Activar suscripción
        $result = $this->subscriptionService->activateSubscription(
            $user,
            $request->invitation_code
        );

        if (!$result['success']) {
            return redirect()->route('plans')
                ->withErrors(['invitation_code' => $result['message']])
                ->withInput();
        }

        return redirect()->route('dashboard')
            ->with('success', $result['message']);
    }
}
