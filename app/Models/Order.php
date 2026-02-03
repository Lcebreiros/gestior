<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'order_number',
        'client_id',
        'client_name',
        'status',
        'payment_status',
        'order_date',
        'delivery_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'payment_deadline',
        'payment_date',
        'subtotal',
        'discount',
        'tax',
        'shipping',
        'total',
        'delivery_address',
        'tracking_number',
        'notes',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'delivery_date' => 'datetime',
        'expected_delivery_date' => 'datetime',
        'actual_delivery_date' => 'datetime',
        'payment_deadline' => 'datetime',
        'payment_date' => 'datetime',
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'tax' => 'decimal:2',
        'shipping' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    /**
     * Relación con el usuario
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación con el cliente
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Relación con eventos del calendario
     */
    public function calendarEvents(): MorphMany
    {
        return $this->morphMany(CalendarEvent::class, 'related');
    }

    /**
     * Scope para pedidos pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para pedidos con pago pendiente
     */
    public function scopePaymentPending($query)
    {
        return $query->whereIn('payment_status', ['pending', 'partial']);
    }

    /**
     * Scope para pedidos vencidos
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_status', '!=', 'paid')
            ->where('payment_deadline', '<', now());
    }

    /**
     * Crear evento de entrega en el calendario
     */
    public function createDeliveryEvent()
    {
        if ($this->delivery_date) {
            CalendarEvent::create([
                'user_id' => $this->user_id,
                'organization_id' => $this->organization_id,
                'event_type' => 'order_delivery',
                'title' => "Entrega: {$this->order_number}",
                'description' => "Entrega del pedido para {$this->client_name}",
                'color' => CalendarEvent::getColorForType('order_delivery'),
                'event_date' => $this->delivery_date,
                'due_date' => $this->delivery_date,
                'status' => 'pending',
                'related_type' => Order::class,
                'related_id' => $this->id,
            ]);
        }
    }

    /**
     * Crear evento de pago en el calendario
     */
    public function createPaymentEvent()
    {
        if ($this->payment_deadline && $this->payment_status !== 'paid') {
            CalendarEvent::create([
                'user_id' => $this->user_id,
                'organization_id' => $this->organization_id,
                'event_type' => 'payment_deadline',
                'title' => "Pago: {$this->order_number}",
                'description' => "Vencimiento de pago - {$this->client_name}",
                'color' => CalendarEvent::getColorForType('payment_deadline'),
                'event_date' => $this->payment_deadline,
                'due_date' => $this->payment_deadline,
                'status' => 'pending',
                'related_type' => Order::class,
                'related_id' => $this->id,
            ]);
        }
    }

    /**
     * Marcar como entregado
     */
    public function markAsDelivered()
    {
        $this->update([
            'status' => 'delivered',
            'actual_delivery_date' => now(),
        ]);

        // Marcar evento de entrega como completado
        $this->calendarEvents()
            ->where('event_type', 'order_delivery')
            ->pending()
            ->each(fn($event) => $event->markAsCompleted());
    }

    /**
     * Marcar como pagado
     */
    public function markAsPaid()
    {
        $this->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        // Marcar evento de pago como completado
        $this->calendarEvents()
            ->where('event_type', 'payment_deadline')
            ->pending()
            ->each(fn($event) => $event->markAsCompleted());
    }
}
