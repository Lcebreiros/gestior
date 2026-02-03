<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class SupplySchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'supply_id',
        'supplier_id',
        'current_stock',
        'reorder_level',
        'reorder_quantity',
        'unit',
        'last_purchase_date',
        'next_reorder_date',
        'expected_delivery_date',
        'expiration_date',
        'last_purchase_cost',
        'estimated_next_cost',
        'status',
        'auto_reorder',
        'lead_time_days',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'reorder_quantity' => 'decimal:2',
        'last_purchase_cost' => 'decimal:2',
        'estimated_next_cost' => 'decimal:2',
        'last_purchase_date' => 'datetime',
        'next_reorder_date' => 'datetime',
        'expected_delivery_date' => 'datetime',
        'expiration_date' => 'datetime',
        'auto_reorder' => 'boolean',
        'lead_time_days' => 'integer',
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
     * Relación con el insumo
     */
    public function supply(): BelongsTo
    {
        return $this->belongsTo(Supply::class);
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
     * Scope para insumos con stock bajo
     */
    public function scopeLowStock($query)
    {
        return $query->where('status', 'low_stock')
            ->orWhereRaw('current_stock <= reorder_level');
    }

    /**
     * Scope para insumos sin stock
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('status', 'out_of_stock')
            ->orWhere('current_stock', '<=', 0);
    }

    /**
     * Scope para reorden automático
     */
    public function scopeAutoReorder($query)
    {
        return $query->where('auto_reorder', true);
    }

    /**
     * Verificar y actualizar estado de stock
     */
    public function updateStockStatus()
    {
        $status = match (true) {
            $this->current_stock <= 0 => 'out_of_stock',
            $this->current_stock <= $this->reorder_level => 'low_stock',
            default => 'in_stock',
        };

        $this->update(['status' => $status]);

        // Si está bajo y hay auto-reorden, crear recordatorio
        if ($status === 'low_stock' && $this->auto_reorder) {
            $this->createReorderEvent();
        }

        return $status;
    }

    /**
     * Crear evento de reorden en el calendario
     */
    public function createReorderEvent()
    {
        if ($this->next_reorder_date) {
            CalendarEvent::create([
                'user_id' => $this->user_id,
                'organization_id' => $this->organization_id,
                'event_type' => 'supply_reorder',
                'title' => "Reorden: {$this->supply->name}",
                'description' => "Stock bajo - Reordenar {$this->reorder_quantity} {$this->unit}",
                'color' => CalendarEvent::getColorForType('supply_reorder'),
                'event_date' => $this->next_reorder_date,
                'due_date' => $this->next_reorder_date,
                'status' => 'pending',
                'related_type' => SupplySchedule::class,
                'related_id' => $this->id,
            ]);
        }
    }

    /**
     * Registrar compra
     */
    public function recordPurchase($quantity, $cost)
    {
        $this->update([
            'current_stock' => $this->current_stock + $quantity,
            'last_purchase_date' => now(),
            'last_purchase_cost' => $cost,
            'expected_delivery_date' => now()->addDays($this->lead_time_days),
            'status' => 'ordered',
        ]);

        // Calcular próxima fecha de reorden
        $this->calculateNextReorderDate();

        // Marcar eventos de reorden como completados
        $this->calendarEvents()
            ->where('event_type', 'supply_reorder')
            ->pending()
            ->each(fn($event) => $event->markAsCompleted());
    }

    /**
     * Calcular próxima fecha de reorden
     */
    public function calculateNextReorderDate()
    {
        // Estimación simple: calcular días hasta alcanzar nivel de reorden
        // Esto debería ser más sofisticado en producción
        $daysOfStock = $this->current_stock / ($this->reorder_quantity / 30); // Consumo promedio

        $nextDate = now()->addDays(max(1, $daysOfStock - $this->lead_time_days));

        $this->update(['next_reorder_date' => $nextDate]);
    }

    /**
     * Verificar expiración
     */
    public function checkExpiration()
    {
        if ($this->expiration_date && $this->expiration_date->isPast()) {
            // Crear alerta de expiración
            CalendarEvent::create([
                'user_id' => $this->user_id,
                'organization_id' => $this->organization_id,
                'event_type' => 'reminder',
                'title' => "Vencido: {$this->supply->name}",
                'description' => "Insumo vencido - Verificar stock",
                'color' => '#EF4444',
                'event_date' => now(),
                'due_date' => now(),
                'status' => 'overdue',
                'related_type' => SupplySchedule::class,
                'related_id' => $this->id,
            ]);
        }
    }
}
