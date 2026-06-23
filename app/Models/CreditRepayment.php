<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CreditRepayment extends Model
{
    protected $fillable = [
        'credit_customer_id',
        'amount',
        'payment_method',
        'received_by',
        'repayment_date',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'repayment_date' => 'date',
    ];

    public function creditCustomer(): BelongsTo
    {
        return $this->belongsTo(CreditCustomer::class);
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }
}
