<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $job_seeker_type
 * @property string|null $first_name
 * @property string|null $middle_name
 * @property string|null $last_name
 * @property string|null $suffix
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property string|null $sex
 * @property string|null $photo
 * @property string|null $civilstatus
 * @property string|null $street
 * @property string|null $barangay
 * @property string|null $municipality
 * @property string|null $province
 * @property string|null $religion
 * @property string|null $contactnumber
 * @property string|null $email
 * @property bool|null $is_4ps
 * @property string|null $employmentstatus
 * @property int|null $education_level_id
 * @property string|null $institution_name
 * @property int|null $graduation_year
 * @property float|null $gpa
 * @property string|null $degree_field
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
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
        // New normalized fields (for informal - single education record)
        'education_level_id',
        'institution_name',
        'graduation_year',
        'gpa',
        'degree_field',
        // JSON field (for formal - multiple education records)
        'education',
    ];

    protected $casts = [
        'birthday' => 'date',
        'is_4ps' => 'boolean',
        'graduation_year' => 'integer',
        'gpa' => 'decimal:2',
        'education' => 'array', // JSON array for formal jobseekers
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
     * Formal verification record for this jobseeker
     */
    public function formalVerification(): HasOne
    {
        return $this->hasOne(FormalJobseekerVerification::class, 'jobseeker_id');
    }

    /**
     * Informal verification record for this jobseeker
     */
    public function informalVerification(): HasOne
    {
        return $this->hasOne(InformalJobseekerVerification::class, 'jobseeker_id');
    }

    /**
     * Get the appropriate verification record based on job seeker type
     */
    public function verification()
    {
        if ($this->job_seeker_type === 'formal') {
            return $this->formalVerification();
        } elseif ($this->job_seeker_type === 'informal') {
            return $this->informalVerification();
        }
        
        return null;
    }

    /**
     * Check if jobseeker has a verification record
     */
    public function hasVerificationRecord(): bool
    {
        if ($this->job_seeker_type === 'formal') {
            return $this->formalVerification !== null;
        } elseif ($this->job_seeker_type === 'informal') {
            return $this->informalVerification !== null;
        }
        
        return false;
    }

    /**
     * Get verification status
     */
    public function getVerificationStatus(): ?string
    {
        $verification = $this->job_seeker_type === 'formal' 
            ? $this->formalVerification 
            : $this->informalVerification;
            
        return $verification?->status;
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
     * Get education records for formal jobseekers (sorted by level)
     * Returns collection of education entries from elementary to latest
     */
    public function getEducationRecords()
    {
        if ($this->job_seeker_type === 'formal' && $this->education) {
            return collect($this->education)->sortBy('level_order');
        }
        return collect();
    }

    /**
     * Check if formal jobseeker has education records
     */
    public function hasEducationRecords(): bool
    {
        return $this->job_seeker_type === 'formal' && 
               $this->education && 
               count($this->education) > 0;
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
