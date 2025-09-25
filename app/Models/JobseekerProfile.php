<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JobseekerProfile extends Model
{
    protected $fillable = [
        'user_id',
        'job_seeker_type', // Added new field for formal/informal status
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'birthday',
        'sex',
        'photo',
        'civilstatus',
        'street',
        'barangay',
        'municipality',
        'province',
        'religion',
        'contactnumber',
        'email',
        'is_4ps',
        'employmentstatus',
        // New normalized fields
        'education_level_id',
        'institution_name',
        'graduation_year',
        'gpa',
        'degree_field',
    ];

    protected $casts = [
        'birthday' => 'date',
        'is_4ps' => 'boolean',
        'graduation_year' => 'integer',
        'gpa' => 'decimal:2',
    ];

    /**
     * The user this profile belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The education level of this jobseeker
     */
    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    /**
     * The skills this jobseeker has
     */
    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'jobseeker_skills')
                    ->withPivot('proficiency_level', 'years_experience')
                    ->withTimestamps();
    }

    /**
     * The disabilities this jobseeker has
     */
    public function disabilities(): BelongsToMany
    {
        return $this->belongsToMany(Disability::class, 'jobseeker_disabilities')
                    ->withPivot('accommodation_needs')
                    ->withTimestamps();
    }

    /**
     * The work experiences of this jobseeker
     */
    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    /**
     * The job applications submitted by this jobseeker
     */
    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    /**
     * The job preferences of this jobseeker
     */
    public function jobPreferences(): HasMany
    {
        return $this->hasMany(JobPreference::class);
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        $name = trim($this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name);
        return $this->suffix ? $name . ' ' . $this->suffix : $name;
    }

    /**
     * Get total years of experience
     */
    public function getTotalExperienceYearsAttribute(): float
    {
        return $this->workExperiences->sum(function ($experience) {
            return $experience->duration_in_months / 12;
        });
    }

    /**
     * Scope for formal job seekers
     */
    public function scopeFormal($query)
    {
        return $query->where('job_seeker_type', 'formal');
    }

    /**
     * Scope for informal job seekers
     */
    public function scopeInformal($query)
    {
        return $query->where('job_seeker_type', 'informal');
    }
}
