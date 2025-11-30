<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;
    
    protected $table = 'maintenance_rqst';
    public $primaryKey = 'request_id';
    protected $fillable = [
        'user_id',
        'unit_id',
        'title',
        'description',
        'priority',
        'status',
        'assigned_staff_id',
        'requested_at',
        'approved_at',
        'completed_at',
    ];

    protected $casts = [
        'requested_at' => 'datetime',
        'approved_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    // Relationships - Fixed to match your actual database structure
    public function tenant()
    {
        return $this->belongsTo(User::class, 'user_id'); // Changed from tenant_id to user_id
    }

    public function unit()
    {
        return $this->belongsTo(PropertyUnits::class, 'unit_id');
    }

    public function property()
    {
        return $this->hasOneThrough(
            Property::class,
            PropertyUnits::class,
            'unit_id', // Foreign key on PropertyUnits table
            'property_id', // Foreign key on Property table
            'unit_id', // Local key on MaintenanceRequest table
            'property_id' // Local key on PropertyUnits table
        );
    }

    public function assignedTechnician()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id'); // Changed from assigned_to to assigned_staff_id
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'PENDING');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'IN_PROGRESS');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'COMPLETED');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'HIGH');
    }

    // Methods
    public function updateStatus($status, $notes = null, $changedBy = null)
    {
        $this->update(['status' => $status]);

        if ($status === 'COMPLETED') {
            $this->update(['completed_at' => now()]);
        }
    }
}