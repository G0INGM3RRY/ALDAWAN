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
 * @property string $verification_status
 * @property \Illuminate\Support\Carbon|null $verification_submitted_at
 * @property \Illuminate\Support\Carbon|null $verified_at
 * @property int|null $verified_by
 * @property string|null $admin_level
 * @property string|null $assigned_region
 * @property string $account_status
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

    // Relationship: User has many verification requests
    public function verificationRequests()
    {
        return $this->hasMany(VerificationRequest::class);
    }

    // Relationship: User who verified this user (admin)
    public function verifiedByAdmin()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // Relationship: Users verified by this admin
    public function verifiedUsers()
    {
        return $this->hasMany(User::class, 'verified_by');
    }

    // Relationship: Admin activity logs
    public function adminActivityLogs()
    {
        return $this->hasMany(AdminActivityLog::class, 'admin_user_id');
    }

    // Helper method: Check if user is admin
    public function isAdmin()
    {
        return !is_null($this->admin_level);
    }

    // Helper method: Check if user is verified
    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    // Helper method: Check if verification is pending
    public function hasVerificationPending()
    {
        return $this->verification_status === 'pending';
    }

    // Helper method: Get verification badge
    public function getVerificationBadge()
    {
        return match($this->verification_status) {
            'verified' => '<span class="badge bg-success">✓ Verified</span>',
            'pending' => '<span class="badge bg-warning">⏳ Pending</span>',
            'rejected' => '<span class="badge bg-danger">✗ Rejected</span>',
            default => '<span class="badge bg-secondary">Unverified</span>'
        };
    }

    // Helper method: Check if user can perform admin actions
    public function canPerformAdminActions()
    {
        return $this->isAdmin() && $this->account_status === 'active';
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
        'verification_status',
        'verification_submitted_at',
        'verified_at',
        'verified_by',
        'admin_level',
        'assigned_region',
        'account_status'
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
            'verification_submitted_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }
}
