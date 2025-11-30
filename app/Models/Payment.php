<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $table = 'payments';
    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'bill_id',
        'lease_id',
        'amount_paid',
        'payment_date',
        'payment_method',
        'transaction_type', 
        'reference_no',
    ];

    protected $casts = [
        'payment_date' => 'datetime',
        'amount_paid' => 'decimal:2',
    ];

    /**
     * Get the billing associated with the payment.
     */
    public function billing(): BelongsTo
    {
        return $this->belongsTo(Billing::class, 'bill_id', 'bill_id');
    }

    /**
     * Get the lease associated with the payment.
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Leases::class, 'lease_id', 'lease_id');
    }
}