<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leases extends Model
{
    protected $table = 'leases';
    public $primaryKey = 'lease_id';

    protected $fillable = [
        'user_id',
        'prop_id',
        'start_date',
        'end_date',
        'rent_amount',
        'deposit_amount',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Properties::class, 'prop_id', 'prop_id');
    }
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
 
}
