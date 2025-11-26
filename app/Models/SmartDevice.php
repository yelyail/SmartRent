<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmartDevice extends Model
{
    protected $table = 'smart_devices';
    protected $primaryKey = 'device_id';

    protected $fillable = [
        'prop_id',
        'device_name',
        'device_type',
        'model',
        'serial_number',
        'connection_status',
        'power_status',
        'battery_level',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'prop_id');
    }
    public function getStatusColorClass($type = 'bg')
    {
        $prefix = $type === 'bg' ? 'bg-' : 'text-';
        
        // If device is offline, use gray colors
        if ($this->connection_status === 'offline') {
            return $prefix . 'gray-100 ' . $prefix . 'gray-600';
        }

        // Otherwise use colors based on device type
        $colors = [
            'thermostat' => 'blue',
            'lock' => 'blue',
            'light' => 'yellow',
            'camera' => 'gray',
            'sensor' => 'green',
            'plug' => 'purple',
            'doorbell' => 'orange',
            'security' => 'red',
            'climate' => 'blue',
            'switch' => 'purple',
            'outlet' => 'green',
        ];

        $deviceType = strtolower($this->device_type);
        $color = $colors[$deviceType] ?? 'blue';
        return $prefix . $color . '-100 ' . $prefix . $color . '-600';
    }

    public function getConnectionStatusColorClass($type = 'bg')
    {
        $prefix = $type === 'bg' ? 'bg-' : 'text-';
        
        return match($this->connection_status) {
            'online' => $prefix . 'green-500',
            'offline' => $prefix . 'red-500',
            'connecting' => $prefix . 'yellow-500',
            default => $prefix . 'gray-500'
        };
    }

    public function getBatteryColorClass()
    {
        if ($this->battery_level === null) return 'bg-gray-500';
        
        return match(true) {
            $this->battery_level <= 20 => 'bg-red-500',
            $this->battery_level <= 50 => 'bg-yellow-500',
            default => 'bg-green-500'
        };
    }
}