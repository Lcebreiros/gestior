<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CalendarEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'event_type',
        'title',
        'description',
        'color',
        'event_date',
        'due_date',
        'reminder_date',
        'completed_at',
        'status',
        'related_type',
        'related_id',
        'metadata',
        'is_recurring',
        'recurrence_pattern',
    ];

    protected $casts = [
        'event_date' => 'datetime',
        'due_date' => 'datetime',
        'reminder_date' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
        'is_recurring' => 'boolean',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación polimórfica con la entidad relacionada
     */
    public function related(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope para eventos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para eventos vencidos
     */
    public function scopeOverdue($query)
    {
        return $query->where('status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('status', 'pending')
                  ->where('due_date', '<', now());
            });
    }

    /**
     * Scope para eventos del mes
     */
    public function scopeInMonth($query, $year, $month)
    {
        return $query->whereYear('event_date', $year)
            ->whereMonth('event_date', $month);
    }

    /**
     * Scope para eventos en rango de fechas
     */
    public function scopeInRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('event_date', [$startDate, $endDate]);
    }

    /**
     * Scope para eventos por tipo
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Marcar como completado
     */
    public function markAsCompleted()
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Marcar como vencido
     */
    public function markAsOverdue()
    {
        $this->update([
            'status' => 'overdue',
        ]);
    }

    /**
     * Obtener color según tipo de evento
     */
    public static function getColorForType($type)
    {
        return match ($type) {
            'order_delivery' => '#0EA5E9',    // Azul cielo
            'payment_deadline' => '#F97316',  // Naranja
            'tax_due' => '#EF4444',           // Rojo
            'supply_reorder' => '#06B6D4',    // Cyan
            'expense_payment' => '#FF8C42',   // Naranja
            'reminder' => '#10B981',          // Verde
            default => '#7534C9',             // Violeta (primario)
        };
    }
}
