<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Property extends Model
{
    protected $table = 'properties';
    protected $primaryKey = 'prop_id';

    protected $fillable = [
        'user_id',
        'property_name',
        'property_address',
        'property_type',
        'property_price',
        'property_description',
        'property_image',
        'status',
    ];

    // Relationship with PropertyUnit
    public function units(): HasMany
    {
        return $this->hasMany(PropertyUnits::class, 'prop_id', 'prop_id');
    }

    // Relationship with SmartDevice
    public function smartDevices(): HasMany
    {
        return $this->hasMany(SmartDevice::class, 'prop_id', 'prop_id');
    }

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Calculate occupancy rate
    public function getOccupancyRateAttribute()
    {
        $totalUnits = $this->units->count();
        $occupiedUnits = $this->units->where('status', 'occupied')->count();
        
        return $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100) : 0;
    }

    // Get available units
    public function getAvailableUnitsAttribute()
    {
        return $this->units->where('status', 'available')->count();
    }

    // Get online devices
    public function getOnlineDevicesAttribute()
    {
        return $this->smartDevices->where('connection_status', 'online')->count();
    }

    // Scope for active properties
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope for properties by user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}