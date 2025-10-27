<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformalJobseekerVerification extends Model
{
    protected $fillable = [
        'jobseeker_id',
        'status',
        'basic_id_path',
        'barangay_clearance_path',
        'health_certificate_path',
        'verification_notes',
        'verified_by',
        'verified_at',
        'submitted_at',
        'rejection_reason'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'submitted_at' => 'datetime',
    ];

    /**
     * The jobseeker profile this verification belongs to
     */
    public function jobseekerProfile(): BelongsTo
    {
        return $this->belongsTo(JobseekerProfile::class, 'jobseeker_id');
    }

    /**
     * The admin user who verified this jobseeker
     */
    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Mark as approved
     */
    public function approve(User $admin, ?string $notes = null): void
    {
        $this->update([
            'status' => 'approved',
            'verified_by' => $admin->id,
            'verified_at' => now(),
            'verification_notes' => $notes
        ]);

        // Update user verification status
        $this->jobseekerProfile->user->update(['verification_status' => 'verified']);
    }

    /**
     * Mark as rejected
     */
    public function reject(User $admin, string $reason): void
    {
        $this->update([
            'status' => 'rejected',
            'verified_by' => $admin->id,
            'verified_at' => now(),
            'rejection_reason' => $reason
        ]);

        // Update user verification status
        $this->jobseekerProfile->user->update(['verification_status' => 'rejected']);
    }

    /**
     * Check if all required documents are uploaded
     */
    public function hasAllRequiredDocuments(): bool
    {
        return !empty($this->basic_id_path) && 
               !empty($this->barangay_clearance_path);
    }

    /**
     * Get required documents list
     */
    public static function getRequiredDocuments(): array
    {
        return [
            'basic_id_path' => 'Government-issued ID (any type)',
            'barangay_clearance_path' => 'Barangay Clearance/Certificate'
        ];
    }

    /**
     * Get optional documents list
     */
    public static function getOptionalDocuments(): array
    {
        return [
            'health_certificate_path' => 'Health Certificate (for food handling/household work)'
        ];
    }

    /**
     * Check if health certificate is required based on job preferences
     */
    public function needsHealthCertificate(): bool
    {
        $jobPreferences = $this->jobseekerProfile->jobPreferences;
        
        foreach ($jobPreferences as $preference) {
            // Check if job preference involves household work or food handling
            $classification = strtolower($preference->preferred_job_classification ?? '');
            if (str_contains($classification, 'household') || 
                str_contains($classification, 'food') ||
                str_contains($classification, 'cook') ||
                str_contains($classification, 'caregiver')) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Scope for pending verifications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved verifications
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected verifications
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}