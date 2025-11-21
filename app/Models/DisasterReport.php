<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisasterReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'reported_at',
        'disaster_type',
        'organization_name',
        'district_id',
        'affiliation_id',
        'current_status',
        'teaching_status',
        'affected_students',
        'injured_students',
        'dead_students',
        'dead_students_list',
        'affected_staff',
        'injured_staff',
        'dead_staff',
        'dead_staff_list',
        'damage_building',
        'damage_equipment',
        'damage_material',
        'damage_total_request',
        'assistance_received',
        'contact_name',
        'contact_position',
        'contact_phone',
        'latitude',
        'longitude',
        'form_hash',
        'is_published',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'damage_building' => 'decimal:2',
        'damage_equipment' => 'decimal:2',
        'damage_material' => 'decimal:2',
        'damage_total_request' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_published' => 'boolean',
    ];

    /**
     * Associated district.
     */
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    /**
     * Associated affiliation.
     */
    public function affiliation(): BelongsTo
    {
        return $this->belongsTo(Affiliation::class);
    }
}
