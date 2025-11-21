<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shelter extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'capacity',
        'district_id',
        'affiliation_id',
        'status',
        'current_occupancy',
        'is_kitchen',
        'contact_name',
        'contact_phone',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function affiliation()
    {
        return $this->belongsTo(Affiliation::class);
    }
}
