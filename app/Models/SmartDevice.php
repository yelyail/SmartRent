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
}
