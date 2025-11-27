<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leases extends Model
{
    protected $table = 'leases';
    protected $primaryKey = 'lease_id';

    protected $fillable = [
        'user_id',
        'prop_id',
        'unit_id',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit_amount',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class, 'prop_id', 'prop_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function unit()
    {
        return $this->belongsTo(PropertyUnits::class, 'unit_id', 'unit_id');
    }
}