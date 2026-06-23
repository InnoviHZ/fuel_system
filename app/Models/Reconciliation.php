<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reconciliation extends Model
{
    protected $fillable = [
        'shift_id',
        'reconciled_by',
        'total_litres_sold',
        'expected_cash',
        'actual_cash',
        'actual_pos',
        'actual_bank_transfer',
        'total_collected',
        'cash_difference',
        'status',
        'supervisor_note',
        'manager_note',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'expected_cash' => 'decimal:2',
        'actual_cash' => 'decimal:2',
        'actual_pos' => 'decimal:2',
        'actual_bank_transfer' => 'decimal:2',
        'total_collected' => 'decimal:2',
        'cash_difference' => 'decimal:2',
        'total_litres_sold' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }

    public function reconciledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reconciled_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isBalanced(): bool
    {
        return $this->status === 'balanced';
    }

    public function isSurplus(): bool
    {
        return $this->status === 'surplus';
    }

    public function isShortage(): bool
    {
        return $this->status === 'shortage';
    }
}
