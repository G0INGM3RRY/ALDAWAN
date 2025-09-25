<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * @property int $id
 * @property string $job_title
 * @property string $description
 * @property int $company_id
 * @property string $location
 * @property float $salary
 * @property string $employment_type
 * @property string $requirements
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $posted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Jobs extends Model
{
    protected $table = 'job_listings'; // Specify the correct table name
    
    protected $fillable = [
        'job_title',
        'description',
        'company_id',
        'location',
        'salary',
        'posted_at',
        'status',
        'job_type',
        // New normalized fields
        'minimum_education_level_id',
        'minimum_experience_years',
        'benefits',
        'employment_type',
        'remote_work_available',
        'positions_available'
    ];
    
    protected $casts = [
        'posted_at' => 'datetime',
        'salary' => 'decimal:2',
        'remote_work_available' => 'boolean',
        'minimum_experience_years' => 'integer',
        'positions_available' => 'integer',
    ];
    
    // Relationship: Job belongs to a User (employer)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    /**
     * The minimum education level required for this job
     */
    public function minimumEducationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class, 'minimum_education_level_id');
    }

    /**
     * The skills required for this job
     */
    public function requiredSkills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'job_skills', 'job_listing_id', 'skill_id')
                    ->withPivot('required_level', 'is_required')
                    ->withTimestamps();
    }

    /**
     * The classifications this job belongs to
     */
    public function classifications(): BelongsToMany
    {
        return $this->belongsToMany(Classification::class, 'job_classifications', 'job_listing_id', 'classification_id')
                    ->withTimestamps();
    }
    
    // Get employer details through user relationship
    public function employerProfile(): HasOneThrough
    {
        return $this->hasOneThrough(Employer::class, User::class, 'id', 'user_id', 'company_id');
    }
    
    // Relationship: Job has many Applications
    public function applications(): HasMany
    {
        return $this->hasMany(\App\Models\JobApplication::class, 'job_id');
    }
    
    // Legacy method name for backward compatibility
    public function formalApplications(): HasMany
    {
        return $this->applications();
    }
    
    // Helper method: Check if user has already applied
    public function hasAppliedBy($userId): bool
    {
        return \App\Models\JobApplication::where('job_id', $this->id)
                                              ->where('user_id', $userId)
                                              ->exists();
    }
    
    // Helper method: Get application count
    public function getApplicationCount(): int
    {
        return $this->applications()->count();
    }

    /**
     * Scope for active jobs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for jobs with remote work available
     */
    public function scopeRemoteAvailable($query)
    {
        return $query->where('remote_work_available', true);
    }
}
