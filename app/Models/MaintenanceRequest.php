<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    protected $table = 'maintenance_rqst';
    protected $primaryKey = 'request_id';

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
        'completed_at'
    ];
    
    protected $casts = [
        'requested_at' => 'datetime',
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(PropertyUnits::class, 'unit_id', 'unit_id');
    }

    public function assignedStaff()
    {
        return $this->belongsTo(User::class, 'assigned_staff_id', 'user_id');
    }
    
    // Add this relationship for billing
    public function billing()
    {
        return $this->hasOne(Billing::class, 'request_id', 'request_id');
    }

    /**
     * Scope for in-progress requests
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for assigned to specific staff
     */
    public function scopeAssignedTo($query, $staffId)
    {
        return $query->where('assigned_staff_id', $staffId);
    }

    /**
     * Get priority badge class for UI
     */
    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-red-100 text-red-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-orange-100 text-orange-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    /**
     * Get human readable status
     */
    public function getStatusHumanAttribute()
    {
        return match($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get human readable priority
     */
    public function getPriorityHumanAttribute()
    {
        return match($this->priority) {
            'urgent' => 'Urgent',
            'high' => 'High',
            'medium' => 'Medium',
            'low' => 'Low',
            default => ucfirst($this->priority)
        };
    }
     public function determinePriority($title, $description)
    {
        $text = strtolower($title . ' ' . $description);
        
        // Urgent keywords (immediate danger)
        $urgentKeywords = [
            'flood', 'fire', 'electrical fire', 'gas leak', 'carbon monoxide',
            'no heat', 'no hot water', 'flooding', 'spark', 'smoke',
            'electrocution', 'broken window', 'door broken'
        ];
        
        // High priority keywords (serious issues)
        $highKeywords = [
            'leak', 'water leak', 'pipe burst', 'no electricity', 'power outage',
            'broken lock', 'security', 'break in', 'theft', 'flooded',
            'mold', 'sewage', 'toilet overflow', 'clogged toilet', 'no water',
            'refrigerator', 'oven', 'stove', 'heater', 'air conditioning',
            'ac broken', 'heat broken', 'security system'
        ];
        
        // Medium priority keywords (needs attention)
        $mediumKeywords = [
            'repair', 'broken', 'damaged', 'cracked', 'loose', 'not working',
            'dripping', 'slow drain', 'noisy', 'squeaky', 'stuck', 'jammed',
            'peeling', 'stain', 'dirty', 'clean', 'maintenance', 'service'
        ];
        
        // Low priority keywords (cosmetic/minor)
        $lowKeywords = [
            'paint', 'touch up', 'decorate', 'cosmetic', 'aesthetic',
            'small crack', 'minor', 'light bulb', 'bulb', 'caulk',
            'weather stripping', 'shelf', 'blind', 'curtain'
        ];
        
        // Check for urgent keywords first
        foreach ($urgentKeywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return 'urgent';
            }
        }
        
        // Check for high priority keywords
        foreach ($highKeywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return 'high';
            }
        }
        
        // Check for medium priority keywords
        foreach ($mediumKeywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return 'medium';
            }
        }
        foreach ($lowKeywords as $keyword) {
            if (strpos($text, $keyword) !== false) {
                return 'low';
            }
        }
        return 'medium';
    }
}