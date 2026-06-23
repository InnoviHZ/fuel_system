<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    protected $fillable = [
        'tank_id',
        'fuel_product_id',
        'supplier_name',
        'waybill_number',
        'quantity_litres',
        'unit_cost',
        'total_cost',
        'stock_before',
        'stock_after',
        'delivered_by',
        'delivery_date',
        'notes',
    ];

    protected $casts = [
        'quantity_litres' => 'decimal:2',
        'unit_cost' => 'decimal:2',
        'total_cost' => 'decimal:2',
        'stock_before' => 'decimal:2',
        'stock_after' => 'decimal:2',
        'delivery_date' => 'date',
    ];

    public function tank(): BelongsTo
    {
        return $this->belongsTo(Tank::class);
    }

    public function fuelProduct(): BelongsTo
    {
        return $this->belongsTo(FuelProduct::class);
    }

    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function getStockIncreaseAttribute(): string
    {
        $increase = $this->stock_after - $this->stock_before;
        return number_format($increase, 2);
    }
}
