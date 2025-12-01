<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Leases;
use App\Models\Property;

class PropertyUnits extends Model
{
    protected $table = 'property_units';
    protected $primaryKey = 'unit_id';

    protected $fillable = [
        'prop_id',
        'unit_name',
        'unit_num',
        'unit_type',
        'area_sqm',
        'unit_price',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'prop_id', 'prop_id');
    }
      public function leases()
    {
        return $this->hasMany(Leases::class, 'unit_id', 'unit_id');
    }

    public function activeLease()
    {
        return $this->hasOne(Leases::class, 'unit_id', 'unit_id')
                    ->where('lease_status', 'active');
    }
    public function maintenanceRequests()
    {
        return $this->hasMany(MaintenanceRequest::class, 'unit_id', 'unit_id');
    }
}