<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
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
     * The jobseeker profiles that have this skill
     */
    public function jobseekerProfiles(): BelongsToMany
    {
        return $this->belongsToMany(JobseekerProfile::class, 'jobseeker_skills')
                    ->withPivot('proficiency_level', 'years_experience')
                    ->withTimestamps();
    }

    /**
     * The jobs that require this skill
     */
    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Jobs::class, 'job_skills', 'skill_id', 'job_listing_id')
                    ->withPivot('required_level', 'is_required')
                    ->withTimestamps();
    }

    /**
     * Scope to get only active skills
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
