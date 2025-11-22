<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Affiliation extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'school_count'];

    /**
     * Related disaster reports.
     */
    public function disasterReports(): HasMany
    {
        return $this->hasMany(DisasterReport::class);
    }

    /**
     * Related schools.
     */
    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }

    /**
     * Related users.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
