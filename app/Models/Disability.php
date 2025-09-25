<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Disability extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The jobseeker profiles that have this disability
     */
    public function jobseekerProfiles(): BelongsToMany
    {
        return $this->belongsToMany(JobseekerProfile::class, 'jobseeker_disabilities')
                    ->withPivot('accommodation_needs')
                    ->withTimestamps();
    }

    /**
     * Scope to get only active disabilities
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('name');
    }
}
