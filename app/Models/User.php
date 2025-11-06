<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomVerifyEmail;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;
    
    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
    
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

    // Relationship: User has one Company Verification (for employers)
    public function companyVerification()
    {
        return $this->hasOne(CompanyVerification::class, 'employer_id');
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
    
    // Relationship: User has many Job Applications (for jobseekers)
    public function jobApplications()
    {
        return $this->hasMany(JobApplication::class);
    }

    // Relationship: Formal jobseeker verification (if formal seeker)
    public function formalVerification()
    {
        return $this->hasOne(FormalJobseekerVerification::class);
    }

    // Relationship: Informal jobseeker verification (if informal seeker)
    public function informalVerification()
    {
        return $this->hasOne(InformalJobseekerVerification::class);
    }

    // Helper method: Check if user is admin
    public function isAdmin()
    {
        return $this->role === 'admin';
    }
    
    // Legacy method name for backward compatibility
    public function formalJobApplications()
    {
        return $this->jobApplications();
    }
    
    // Helper method: Get applications for jobs posted by this employer
    public function receivedApplications()
    {
        return JobApplication::whereHas('job', function($query) {
            $query->where('company_id', $this->id);
        });
    }

    public function employer(){
        return $this->hasOne(Employer::class);
    }

    // Relationship: Messages sent by this user
    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // Relationship: Messages received by this user
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    // Helper method: Get unread message count
    public function unreadMessagesCount()
    {
        return $this->receivedMessages()
            ->whereNull('read_at')
            ->where('is_deleted_by_receiver', false)
            ->count();
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
        'role',
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
