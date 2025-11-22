<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KitchenProduction extends Model
{
    protected $fillable = [
        'kitchen_id',
        'production_date',
        'water_bottles',
        'food_boxes',
        'notes',
    ];

    protected $casts = [
        'production_date' => 'date',
        'water_bottles' => 'integer',
        'food_boxes' => 'integer',
    ];

    public function kitchen(): BelongsTo
    {
        return $this->belongsTo(Kitchen::class);
    }
}
