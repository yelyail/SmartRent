<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Properties extends Model
{
    protected $table = 'properties';

    protected $primaryKey = 'property_id';

    protected $fillable = [
        'user_id',
        'property_name',
        'property_address',
        'property_type',
        'property_price',
        'property_description',
        'property_image',
        'property_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}
