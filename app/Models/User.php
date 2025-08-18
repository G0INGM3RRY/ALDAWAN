<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
