<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $user_id
 * @property int $job_id
 * @property string $status
 * @property string|null $cover_letter
 * @property string|null $resume_file_path
 * @property array|null $additional_documents
 * @property \Illuminate\Support\Carbon|null $applied_at
 * @property \Illuminate\Support\Carbon|null $reviewed_at
 * @property \Illuminate\Support\Carbon|null $status_updated_at
 * @property string|null $employer_notes
 * @property string|null $rejection_reason
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class FormalJobApplication extends Model
{
    use HasFactory;
    
    protected $table = 'formal_job_applications';
    
    protected $fillable = [
        'user_id',
        'job_id', 
        'status',
        'cover_letter',
        'resume_file_path',
        'additional_documents',
        'applied_at',
        'reviewed_at',
        'status_updated_at',
        'employer_notes',
        'rejection_reason'
    ];
    
    protected $casts = [
        'additional_documents' => 'array', // JSON field
        'applied_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'status_updated_at' => 'datetime',
    ];
    
    // Relationships
    
    /**
     * The jobseeker who submitted this application
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * The jobseeker profile (for easier access to profile data)
     */
    public function jobseekerProfile()
    {
        return $this->hasOneThrough(JobseekerProfile::class, User::class, 'id', 'user_id', 'user_id', 'id');
    }
    
    /**
     * The job being applied to
     */
    public function job()
    {
        return $this->belongsTo(Jobs::class, 'job_id');
    }
    
    /**
     * The employer who posted the job
     */
    public function employer()
    {
        return $this->hasOneThrough(User::class, Jobs::class, 'id', 'id', 'job_id', 'company_id');
    }
    
    // Helper methods
    
    /**
     * Check if application can be withdrawn
     */
    public function canBeWithdrawn()
    {
        return in_array($this->status, ['pending', 'under_review']);
    }
    
    /**
     * Check if application is still active
     */
    public function isActive()
    {
        return !in_array($this->status, ['accepted', 'rejected']);
    }
    
    /**
     * Get status badge color for UI
     */
    public function getStatusBadgeColor()
    {
        return match($this->status) {
            'pending' => 'warning',
            'under_review' => 'info', 
            'shortlisted' => 'primary',
            'accepted' => 'success',
            'rejected' => 'danger',
            default => 'secondary'
        };
    }
}
