<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kitchen extends Model
{
    protected $fillable = [
        'name',
        'district_id',
        'affiliation_id',
        'status',
        'contact_name',
        'contact_phone',
        'latitude',
        'longitude',
        'facilities',
        'water_bottles',
        'food_boxes',
        'notes',
    ];

    protected $casts = [
        'facilities' => 'array',
        'water_bottles' => 'integer',
        'food_boxes' => 'integer',
    ];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function affiliation()
    {
        return $this->belongsTo(Affiliation::class);
    }

    public function productions()
    {
        return $this->hasMany(KitchenProduction::class)->orderBy('production_date', 'desc');
    }
}
