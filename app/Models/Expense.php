<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'organization_id',
        'expense_type',
        'name',
        'description',
        'amount',
        'currency',
        'supplier_id',
        'frequency',
        'expense_date',
        'due_date',
        'payment_date',
        'next_occurrence_date',
        'last_occurrence_date',
        'payment_status',
        'payment_method',
        'reference_number',
        'metadata',
    ];

    protected $casts = [
        'expense_date' => 'datetime',
        'due_date' => 'datetime',
        'payment_date' => 'datetime',
        'next_occurrence_date' => 'datetime',
        'last_occurrence_date' => 'datetime',
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el proveedor
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relación con eventos del calendario
     */
    public function calendarEvents(): MorphMany
    {
        return $this->morphMany(CalendarEvent::class, 'related');
    }

    /**
     * Scope para gastos pendientes de pago
     */
    public function scopePending($query)
    {
        return $query->where('payment_status', 'pending');
    }

    /**
     * Scope para gastos vencidos
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue')
            ->orWhere(function ($q) {
                $q->where('payment_status', 'pending')
                  ->where('due_date', '<', now());
            });
    }

    /**
     * Scope para gastos recurrentes
     */
    public function scopeRecurring($query)
    {
        return $query->where('frequency', '!=', 'once');
    }

    /**
     * Scope por tipo de gasto
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('expense_type', $type);
    }

    /**
     * Crear evento en el calendario
     */
    public function createCalendarEvent()
    {
        if ($this->due_date) {
            CalendarEvent::create([
                'user_id' => $this->user_id,
                'organization_id' => $this->organization_id,
                'event_type' => 'expense_payment',
                'title' => "Pago: {$this->name}",
                'description' => "Vencimiento de {$this->expense_type} - \${$this->amount}",
                'color' => CalendarEvent::getColorForType('expense_payment'),
                'event_date' => $this->due_date,
                'due_date' => $this->due_date,
                'status' => 'pending',
                'related_type' => Expense::class,
                'related_id' => $this->id,
                'is_recurring' => $this->frequency !== 'once',
                'recurrence_pattern' => $this->frequency,
            ]);
        }
    }

    /**
     * Marcar como pagado
     */
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
            'last_occurrence_date' => now(),
        ]);

        // Si es recurrente, calcular próxima fecha
        if ($this->frequency !== 'once') {
            $this->calculateNextOccurrence();
        }

        // Marcar evento como completado
        $this->calendarEvents()
            ->pending()
            ->each(fn($event) => $event->markAsCompleted());
    }

    /**
     * Calcular próxima ocurrencia para gastos recurrentes
     */
    public function calculateNextOccurrence()
    {
        $baseDate = $this->next_occurrence_date ?? $this->due_date ?? now();

        $nextDate = match ($this->frequency) {
            'daily' => $baseDate->addDay(),
            'weekly' => $baseDate->addWeek(),
            'monthly' => $baseDate->addMonth(),
            'quarterly' => $baseDate->addMonths(3),
            'yearly' => $baseDate->addYear(),
            default => null,
        };

        if ($nextDate) {
            $this->update(['next_occurrence_date' => $nextDate]);

            // Crear próximo evento en el calendario
            $this->createCalendarEvent();
        }
    }

    /**
     * Marcar como vencido
     */
    public function markAsOverdue()
    {
        $this->update(['payment_status' => 'overdue']);

        $this->calendarEvents()
            ->pending()
            ->each(fn($event) => $event->markAsOverdue());
    }
}
