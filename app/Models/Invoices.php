<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoices extends Model
{
    protected $table = 'invoices';
    protected $primaryKey = 'invoice_id';

    protected $fillable = [
        'lease_id',
        'invoice_no',
        'subtotal',
        'late_fees',
        'other_charges',
        'total_amount',
        'status',
        'invoice_date',
        'due_date',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'late_fees' => 'decimal:2',
        'other_charges' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    /**
     * Get the lease associated with the invoice.
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Leases::class, 'lease_id', 'lease_id');
    }
}