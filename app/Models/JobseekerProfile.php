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
        'job_seeker_type',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'jobseeker_skills')
                    ->withPivot('proficiency_level', 'years_experience')
                    ->withTimestamps();
    }

    public function disabilities(): BelongsToMany
    {
        return $this->belongsToMany(Disability::class, 'jobseeker_disabilities')
                    ->withPivot('accommodation_needs')
                    ->withTimestamps();
    }

    public function workExperiences(): HasMany
    {
        return $this->hasMany(WorkExperience::class);
    }

    public function jobApplications(): HasMany
    {
        return $this->hasMany(JobApplication::class);
    }

    public function jobPreferences(): HasMany
    {
        return $this->hasMany(JobPreference::class, 'user_id', 'user_id');
    }

    public function scopeFormal($query)
    {
        return $query->where('job_seeker_type', 'formal');
    }

    public function scopeInformal($query)
    {
        return $query->where('job_seeker_type', 'informal');
    }
}
