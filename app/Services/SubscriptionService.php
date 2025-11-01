<?php

namespace App\Services;

use App\Models\Invitation;
use App\Models\InvitationHistory;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SubscriptionService
{
    /**
     * Planes de suscripción disponibles
     */
    public const PLANS = [
        'basic' => [
            'name' => 'Básico',
            'price' => 0,
            'features' => [
                'Hasta 5 usuarios',
                'Almacenamiento limitado',
                'Soporte por email',
            ],
            'user_limit' => 5,
            'branch_limit' => 1,
        ],
        'premium' => [
            'name' => 'Premium',
            'price' => 0,
            'features' => [
                'Hasta 50 usuarios',
                'Almacenamiento extendido',
                'Soporte prioritario',
                'Reportes avanzados',
            ],
            'user_limit' => 50,
            'branch_limit' => 5,
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'price' => 0,
            'features' => [
                'Usuarios ilimitados',
                'Almacenamiento ilimitado',
                'Soporte 24/7',
                'Reportes personalizados',
                'API completa',
            ],
            'user_limit' => null, // ilimitado
            'branch_limit' => null, // ilimitado
        ],
    ];

    /**
     * Asigna un plan de suscripción a un usuario
     */
    public function assignPlan(User $user, string $planLevel): bool
    {
        if (!array_key_exists($planLevel, self::PLANS)) {
            return false;
        }

        $plan = self::PLANS[$planLevel];

        $user->update([
            'subscription_level' => $planLevel,
            'user_limit' => $plan['user_limit'],
            'branch_limit' => $plan['branch_limit'],
        ]);

        return true;
    }

    /**
     * Valida un código de invitación
     */
    public function validateInvitationCode(string $code): ?Invitation
    {
        // Generar fingerprint del código
        $fingerprint = hash_hmac('sha256', $code, config('app.key'));

        // Buscar invitación por fingerprint
        $invitation = Invitation::where('key_fingerprint', $fingerprint)
            ->where('status', Invitation::STATUS_PENDING)
            ->first();

        if (!$invitation) {
            return null;
        }

        // Verificar que no haya expirado
        if ($invitation->isExpired()) {
            $invitation->update(['status' => Invitation::STATUS_EXPIRED]);
            return null;
        }

        // Verificar el código con bcrypt
        if (!Hash::check($code, $invitation->key_hash)) {
            return null;
        }

        return $invitation;
    }

    /**
     * Activa una suscripción usando un código de invitación
     */
    public function activateSubscription(User $user, string $code): array
    {
        return DB::transaction(function () use ($user, $code) {
            // Validar código
            $invitation = $this->validateInvitationCode($code);

            if (!$invitation) {
                return [
                    'success' => false,
                    'message' => 'Código de invitación inválido o expirado',
                ];
            }

            // Verificar que el plan coincida
            if ($invitation->subscription_level !== $user->subscription_level) {
                return [
                    'success' => false,
                    'message' => 'El código no corresponde al plan seleccionado',
                ];
            }

            // Marcar invitación como usada
            $invitation->update([
                'status' => Invitation::STATUS_USED,
                'used_at' => Carbon::now(),
                'used_by' => $user->id,
                'key_plain' => null, // Eliminar la key plain
            ]);

            // Guardar en historial
            InvitationHistory::create([
                'invitation_id' => $invitation->id,
                'key' => substr($code, 0, 8) . '***', // Solo primeros caracteres
                'email' => $user->email,
                'notes' => $invitation->notes,
                'used_at' => Carbon::now(),
                'used_by' => $user->id,
                'payload' => [
                    'subscription_level' => $invitation->subscription_level,
                    'invitation_type' => $invitation->invitation_type,
                ],
            ]);

            // Activar usuario (mantiene hierarchy_level original = HIERARCHY_COMPANY)
            $user->update([
                'is_active' => true,
            ]);

            return [
                'success' => true,
                'message' => 'Suscripción activada correctamente',
                'invitation' => $invitation,
            ];
        });
    }

    /**
     * Verifica si un usuario tiene una suscripción activa
     */
    public function hasActiveSubscription(User $user): bool
    {
        return $user->is_active && !is_null($user->subscription_level);
    }

    /**
     * Verifica si un usuario necesita activar su suscripción
     */
    public function needsActivation(User $user): bool
    {
        // Usuario ya tiene suscripción activa
        if ($this->hasActiveSubscription($user)) {
            return false;
        }

        // Usuario no ha seleccionado un plan
        if (is_null($user->subscription_level)) {
            return false;
        }

        // Usuario seleccionó plan pero no lo ha activado
        return !$user->is_active;
    }

    /**
     * Obtiene información de un plan
     */
    public static function getPlanInfo(string $planLevel): ?array
    {
        return self::PLANS[$planLevel] ?? null;
    }

    /**
     * Obtiene todos los planes disponibles
     */
    public static function getAllPlans(): array
    {
        return self::PLANS;
    }
}
