<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    // Relationship: User has many Jobs (for employers posting jobs)
    public function jobs()
    {
        return $this->hasMany(Jobs::class, 'company_id');
    }
    
    // Relationship: User has one Employer profile
    public function employerProfile()
    {
        return $this->hasOne(Employer::class);
    }

    // Relationship: User has one Jobseeker profile
    public function jobseekerProfile()
    {
        return $this->hasOne(JobseekerProfile::class);
    }

    // Relationship: User has many Job Preferences (for jobseekers)
    public function jobPreferences()
    {
        return $this->hasMany(JobPreference::class);
    }
    
    // Relationship: User has many Formal Job Applications (for jobseekers)
    public function formalJobApplications()
    {
        return $this->hasMany(FormalJobApplication::class);
    }
    
    // Helper method: Get applications for jobs posted by this employer
    public function receivedApplications()
    {
        return FormalJobApplication::whereHas('job', function($query) {
            $query->where('company_id', $this->id);
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
