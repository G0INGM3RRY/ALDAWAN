<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EducationLevel extends Model
{
    protected $fillable = [
        'name',
        'description',
        'level_order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The jobseeker profiles with this education level
     */
    public function jobseekerProfiles(): HasMany
    {
        return $this->hasMany(JobseekerProfile::class);
    }

    /**
     * The jobs requiring this minimum education level
     */
    public function jobsRequiringMinimum(): HasMany
    {
        return $this->hasMany(Jobs::class, 'minimum_education_level_id');
    }

    /**
     * Scope to get only active education levels
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('level_order');
    }
}
