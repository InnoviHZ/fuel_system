<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CreditCustomer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'credit_limit',
        'current_balance',
        'is_active',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(CreditRepayment::class);
    }

    public function getAvailableCreditAttribute(): float
    {
        return (float) ($this->credit_limit - $this->current_balance);
    }

    public function getCreditUsagePercentageAttribute(): float
    {
        if ($this->credit_limit == 0) {
            return 0;
        }
        return ($this->current_balance / $this->credit_limit) * 100;
    }

    public function isOverLimit(): bool
    {
        return $this->current_balance > $this->credit_limit;
    }

    public function canMakePurchase(float $amount): bool
    {
        return ($this->current_balance + $amount) <= $this->credit_limit;
    }
}
