<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tank extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'fuel_product_id',
        'capacity_litres',
        'current_stock_litres',
        'low_stock_threshold',
        'critical_stock_threshold',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'capacity_litres' => 'decimal:2',
            'current_stock_litres' => 'decimal:2',
            'low_stock_threshold' => 'decimal:2',
            'critical_stock_threshold' => 'decimal:2',
        ];
    }

    /**
     * Get the fuel product for this tank
     */
    public function fuelProduct(): BelongsTo
    {
        return $this->belongsTo(FuelProduct::class);
    }

    /**
     * Get the pumps for this tank
     */
    public function pumps(): HasMany
    {
        return $this->hasMany(Pump::class);
    }

    /**
     * Get active pumps for this tank
     */
    public function activePumps()
    {
        return $this->pumps()->where('is_active', true);
    }

    /**
     * Calculate stock percentage
     */
    public function getStockPercentage(): float
    {
        if ($this->capacity_litres == 0) {
            return 0;
        }
        return ($this->current_stock_litres / $this->capacity_litres) * 100;
    }

    /**
     * Check if tank stock is critical
     */
    public function isCriticalStock(): bool
    {
        return $this->current_stock_litres <= $this->critical_stock_threshold;
    }

    /**
     * Check if tank stock is low
     */
    public function isLowStock(): bool
    {
        return $this->current_stock_litres <= $this->low_stock_threshold 
            && $this->current_stock_litres > $this->critical_stock_threshold;
    }

    /**
     * Get stock alerts for this tank
     */
    public function stockAlerts(): HasMany
    {
        return $this->hasMany(StockAlert::class);
    }

    /**
     * Get unresolved stock alerts
     */
    public function activeAlerts()
    {
        return $this->stockAlerts()->where('is_resolved', false);
    }
}
