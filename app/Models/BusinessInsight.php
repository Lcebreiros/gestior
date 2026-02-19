<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessInsight extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'type',
        'priority',
        'title',
        'description',
        'metadata',
        'action_label',
        'action_route',
        'is_dismissed',
        'dedupe_key',
        'priority_color',
        'type_icon',
        'expires_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_dismissed' => 'boolean',
        'expires_at' => 'datetime',
    ];

    protected $hidden = [
        'dedupe_key',
    ];

    // --- Tipos ---
    public const TYPE_STOCK_ALERT = 'stock_alert';
    public const TYPE_REVENUE_OPPORTUNITY = 'revenue_opportunity';
    public const TYPE_COST_WARNING = 'cost_warning';
    public const TYPE_CLIENT_RETENTION = 'client_retention';
    public const TYPE_TREND = 'trend';
    public const TYPE_PREDICTION = 'prediction';
    public const TYPE_REMINDER = 'reminder';

    // --- Prioridades ---
    public const PRIORITY_CRITICAL = 'critical';
    public const PRIORITY_HIGH = 'high';
    public const PRIORITY_MEDIUM = 'medium';
    public const PRIORITY_LOW = 'low';

    // --- Colores por prioridad ---
    public const PRIORITY_COLORS = [
        'critical' => '#EF4444',
        'high' => '#F97316',
        'medium' => '#3B82F6',
        'low' => '#22C55E',
    ];

    // --- Íconos por tipo ---
    public const TYPE_ICONS = [
        'stock_alert' => 'inventory',
        'revenue_opportunity' => 'trending_up',
        'cost_warning' => 'warning',
        'client_retention' => 'people',
        'trend' => 'show_chart',
        'prediction' => 'psychology',
        'reminder' => 'notifications',
    ];

    // --- Relaciones ---

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // --- Scopes ---

    public function scopeActive($query)
    {
        return $query->where('is_dismissed', false)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeForOrganization($query, $organizationId)
    {
        return $query->where('organization_id', $organizationId);
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOfPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // --- Helpers ---

    public static function priorityColor(string $priority): string
    {
        return self::PRIORITY_COLORS[$priority] ?? '#6B7280';
    }

    public static function typeIcon(string $type): string
    {
        return self::TYPE_ICONS[$type] ?? 'lightbulb';
    }
}
