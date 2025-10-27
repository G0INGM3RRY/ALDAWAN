<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FormalJobseekerVerification extends Model
{
    protected $fillable = [
        'jobseeker_id',
        'status',
        'government_id_path',
        'educational_document_path',
        'skills_certificate_path',
        'nbi_clearance_path',
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
        return !empty($this->government_id_path) && 
               !empty($this->educational_document_path) && 
               !empty($this->nbi_clearance_path);
    }

    /**
     * Get required documents list
     */
    public static function getRequiredDocuments(): array
    {
        return [
            'government_id_path' => 'Government-issued ID',
            'educational_document_path' => 'Educational Certificate/Diploma',
            'nbi_clearance_path' => 'NBI Clearance'
        ];
    }

    /**
     * Get optional documents list
     */
    public static function getOptionalDocuments(): array
    {
        return [
            'skills_certificate_path' => 'Professional Skills Certificate'
        ];
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