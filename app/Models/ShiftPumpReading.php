<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShiftPumpReading extends Model
{
    protected $fillable = [
        'shift_id',
        'pump_id',
        'attendant_id',
        'opening_meter',
        'closing_meter',
        'litres_sold',
    ];

    protected $casts = [
        'opening_meter' => 'decimal:2',
        'closing_meter' => 'decimal:2',
        'litres_sold' => 'decimal:2',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function pump(): BelongsTo
    {
        return $this->belongsTo(Pump::class);
    }

    public function attendant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attendant_id');
    }
}
