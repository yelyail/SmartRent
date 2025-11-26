<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}