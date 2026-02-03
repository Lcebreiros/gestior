<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supply extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'organization_id',
        'name',
        'description',
        'supplier_id',
        'base_unit',
        'stock_base_qty',
        'avg_cost_per_base',
        'min_stock',
        'max_stock',
        'sku',
        'barcode',
        'category',
        'notes',
    ];

    protected $casts = [
        'stock_base_qty' => 'decimal:2',
        'avg_cost_per_base' => 'decimal:2',
        'min_stock' => 'decimal:2',
        'max_stock' => 'decimal:2',
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
     * Relación con programación de compra
     */
    public function schedule(): HasOne
    {
        return $this->hasOne(SupplySchedule::class);
    }
}
