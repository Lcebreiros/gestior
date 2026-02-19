<?php

namespace App\Services\Insights;

use App\Models\User;

interface InsightGeneratorInterface
{
    /**
     * Genera insights y devuelve array de datos listos para crear.
     *
     * Cada elemento debe tener: type, priority, title, description, metadata,
     * action_label, action_route, dedupe_key, expires_at (nullable)
     *
     * @return array<int, array<string, mixed>>
     */
    public function generate(User $user, ?int $organizationId): array;
}
