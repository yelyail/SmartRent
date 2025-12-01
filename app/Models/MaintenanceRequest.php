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
     * Automatically determine priority based on title and description
     */
    public function determinePriority($title, $description)
    {
        $content = strtolower($title . ' ' . $description);
        
        // Emergency keywords - urgent
        $emergencyKeywords = [
            'fire', 'flood', 'flooding', 'gas leak', 'carbon monoxide', 'electrical fire',
            'sparking', 'smoke', 'no heat', 'no water', 'broken window', 'break in',
            'lock broken', 'door broken', 'security breach', 'sewage', 'overflow',
            'burst pipe', 'electrical hazard', 'sparking outlet', 'water pouring'
        ];

        // High priority keywords - urgent but not emergency
        $highPriorityKeywords = [
            'leak', 'leaking', 'water damage', 'no ac', 'no heat', 'hot water',
            'toilet not working', 'clogged', 'overflowing', 'electrical issue',
            'power outage', 'outlet not working', 'window broken', 'lock not working',
            'refrigerator not working', 'oven not working', 'fridge broken'
        ];

        // Medium priority keywords - important but can wait
        $mediumPriorityKeywords = [
            'dripping', 'slow drain', 'light not working', 'bulb replacement',
            'painting', 'carpet cleaning', 'minor repair', 'squeaky door',
            'loose handle', 'cabinet repair', 'countertop', 'flooring'
        ];

        // Low priority keywords - cosmetic or minor issues
        $lowPriorityKeywords = [
            'touch up', 'cosmetic', 'paint chip', 'small crack', 'minor scratch',
            'cleaning', 'dust', 'landscaping', 'curb appeal', 'decorative'
        ];

        // Check for emergency issues first
        foreach ($emergencyKeywords as $keyword) {
            if (str_contains($content, $keyword)) {
                return 'urgent';
            }
        }

        // Check for high priority issues
        foreach ($highPriorityKeywords as $keyword) {
            if (str_contains($content, $keyword)) {
                return 'high';
            }
        }

        // Check for medium priority issues
        foreach ($mediumPriorityKeywords as $keyword) {
            if (str_contains($content, $keyword)) {
                return 'medium';
            }
        }

        // Check for low priority issues
        foreach ($lowPriorityKeywords as $keyword) {
            if (str_contains($content, $keyword)) {
                return 'low';
            }
        }

        // Default to medium priority if no keywords match
        return 'medium';
    }
    
    public function isEmergencyRequest()
    {
        $content = strtolower($this->title . ' ' . $this->description);
        
        $emergencyKeywords = [
            'fire', 'flood', 'gas leak', 'carbon monoxide', 'electrical fire',
            'sparking', 'smoke', 'break in', 'security breach', 'burst pipe'
        ];

        foreach ($emergencyKeywords as $keyword) {
            if (str_contains($content, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get priority color for UI
     */
    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'medium' => 'yellow',
            'low' => 'green',
            default => 'gray'
        };
    }
    
    /**
     * Get priority badge class for UI
     */
    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            'urgent' => 'bg-red-100 text-red-800',
            'high' => 'bg-orange-100 text-orange-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }
    
    /**
     * Check if request has an associated billing
     */
    public function getHasBillingAttribute()
    {
        return $this->billing()->exists();
    }
}