<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pump extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'tank_id',
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
        ];
    }

    /**
     * Get the tank for this pump
     */
    public function tank(): BelongsTo
    {
        return $this->belongsTo(Tank::class);
    }

    /**
     * Get the fuel product for this pump (through tank)
     */
    public function getFuelProductAttribute()
    {
        return $this->tank->fuelProduct;
    }

    /**
     * Get fuel type code (through tank and fuel product)
     */
    public function getFuelTypeAttribute()
    {
        return $this->tank->fuelProduct->code ?? 'N/A';
    }

    /**
     * Get sales for this pump
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Get shift pump readings for this pump
     */
    public function shiftReadings()
    {
        return $this->hasMany(ShiftPumpReading::class);
    }
}
