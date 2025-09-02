<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
 * @property string $classification
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
        'employment_type',
        'requirements',
        'posted_at',
        'status',
        'classification'
    ];
    
    // Relationship: Job belongs to a User (employer)
    public function user()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
    
    // Get employer details through user relationship
    public function employerProfile()
    {
        return $this->hasOneThrough(Employer::class, User::class, 'id', 'user_id', 'company_id');
    }
    
    // Relationship: Job has many Formal Applications
    public function formalApplications()
    {
        return $this->hasMany(\App\Models\FormalJobApplication::class, 'job_id');
    }
    
    // Helper method: Check if user has already applied
    public function hasAppliedBy($userId)
    {
        return \App\Models\FormalJobApplication::where('job_id', $this->id)
                                              ->where('user_id', $userId)
                                              ->exists();
    }
    
    // Helper method: Get application count
    public function getApplicationCount()
    {
        return $this->formalApplications()->count();
    }
}
