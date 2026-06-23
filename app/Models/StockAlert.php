<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockAlert extends Model
{
    protected $fillable = [
        'tank_id',
        'alert_type',
        'stock_level_at_alert',
        'is_resolved',
        'resolved_at',
    ];

    protected $casts = [
        'stock_level_at_alert' => 'decimal:2',
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    public function tank(): BelongsTo
    {
        return $this->belongsTo(Tank::class);
    }

    public function isLowStock(): bool
    {
        return $this->alert_type === 'low_stock';
    }

    public function isCritical(): bool
    {
        return $this->alert_type === 'critical_stock';
    }
}
