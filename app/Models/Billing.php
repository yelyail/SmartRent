<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Billing extends Model
{
    protected $table = 'billings';
    protected $primaryKey = 'bill_id';

    protected $fillable = [
        'lease_id',
        'request_id',
        'bill_name',
        'bill_period',
        'due_date',
        'late_fee',
        'overdue_amount_percent',
        'amount',
        'status',
    ];

    protected $casts = [
        'late_fee' => 'decimal:2',
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    /**
     * Get the lease associated with the billing.
     */
    public function lease(): BelongsTo
    {
        return $this->belongsTo(Leases::class, 'lease_id', 'lease_id');
    }
    public function maintenanceRequest(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRequest::class, 'request_id', 'request_id');
    }
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'bill_id', 'bill_id');
    }
    public function getTotalPaidAttribute()
    {
        return $this->payments()->sum('amount_paid');
    }
    public function getRemainingBalanceAttribute()
    {
        return $this->amount - $this->total_paid;
    }
}