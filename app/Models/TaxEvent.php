<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class TaxEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'tax_type',
        'name',
        'description',
        'estimated_amount',
        'actual_amount',
        'period',
        'period_start',
        'period_end',
        'filing_deadline',
        'payment_deadline',
        'filed_date',
        'paid_date',
        'filing_status',
        'payment_status',
        'reference_number',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'estimated_amount' => 'decimal:2',
        'actual_amount' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'filing_deadline' => 'datetime',
        'payment_deadline' => 'datetime',
        'filed_date' => 'datetime',
        'paid_date' => 'datetime',
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
     * Relación con eventos del calendario
     */
    public function calendarEvents(): MorphMany
    {
        return $this->morphMany(CalendarEvent::class, 'related');
    }

    /**
     * Scope para impuestos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('filing_status', 'pending')
            ->orWhere('payment_status', 'pending');
    }

    /**
     * Scope para impuestos vencidos
     */
    public function scopeOverdue($query)
    {
        return $query->where('filing_status', 'overdue')
            ->orWhere('payment_status', 'overdue');
    }

    /**
     * Scope por tipo de impuesto
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('tax_type', $type);
    }

    /**
     * Crear evento de presentación en el calendario
     */
    public function createFilingEvent()
    {
        CalendarEvent::create([
            'user_id' => $this->user_id,
            'organization_id' => $this->organization_id,
            'event_type' => 'tax_due',
            'title' => "Presentación: {$this->name}",
            'description' => "Vencimiento de presentación de {$this->tax_type}",
            'color' => CalendarEvent::getColorForType('tax_due'),
            'event_date' => $this->filing_deadline,
            'due_date' => $this->filing_deadline,
            'status' => 'pending',
            'related_type' => TaxEvent::class,
            'related_id' => $this->id,
        ]);
    }

    /**
     * Crear evento de pago en el calendario
     */
    public function createPaymentEvent()
    {
        CalendarEvent::create([
            'user_id' => $this->user_id,
            'organization_id' => $this->organization_id,
            'event_type' => 'tax_due',
            'title' => "Pago: {$this->name}",
            'description' => "Vencimiento de pago de {$this->tax_type} - \${$this->estimated_amount}",
            'color' => CalendarEvent::getColorForType('tax_due'),
            'event_date' => $this->payment_deadline,
            'due_date' => $this->payment_deadline,
            'status' => 'pending',
            'related_type' => TaxEvent::class,
            'related_id' => $this->id,
        ]);
    }

    /**
     * Marcar como presentado
     */
    public function markAsFiled()
    {
        $this->update([
            'filing_status' => 'filed',
            'filed_date' => now(),
        ]);
    }

    /**
     * Marcar como pagado
     */
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'paid_date' => now(),
        ]);

        // Marcar eventos como completados
        $this->calendarEvents()
            ->pending()
            ->each(fn($event) => $event->markAsCompleted());
    }

    /**
     * Generar próximo período fiscal
     */
    public function generateNextPeriod()
    {
        $nextStart = match ($this->period) {
            'monthly' => $this->period_start->addMonth(),
            'quarterly' => $this->period_start->addMonths(3),
            'biannual' => $this->period_start->addMonths(6),
            'yearly' => $this->period_start->addYear(),
            default => null,
        };

        if ($nextStart) {
            $nextEnd = match ($this->period) {
                'monthly' => $nextStart->copy()->endOfMonth(),
                'quarterly' => $nextStart->copy()->addMonths(3)->subDay(),
                'biannual' => $nextStart->copy()->addMonths(6)->subDay(),
                'yearly' => $nextStart->copy()->addYear()->subDay(),
                default => null,
            };

            // Crear nuevo evento fiscal
            return self::create([
                'user_id' => $this->user_id,
                'organization_id' => $this->organization_id,
                'tax_type' => $this->tax_type,
                'name' => $this->name,
                'description' => $this->description,
                'period' => $this->period,
                'period_start' => $nextStart,
                'period_end' => $nextEnd,
                'filing_deadline' => $nextEnd->copy()->addDays(15),
                'payment_deadline' => $nextEnd->copy()->addDays(20),
                'filing_status' => 'pending',
                'payment_status' => 'pending',
            ]);
        }

        return null;
    }
}
