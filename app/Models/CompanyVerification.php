<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyVerification extends Model
{
    protected $fillable = [
        'employer_id',
        'status',
        'business_registration_number',
        'tax_id',
        'verification_document_path',
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
     * The employer this verification belongs to
     */
    public function employer(): BelongsTo
    {
        return $this->belongsTo(Employer::class);
    }

    /**
     * The admin user who verified this company
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

        // Also update employer verification status
        $this->employer->update(['is_verified' => true]);
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

        // Ensure employer is not marked as verified
        $this->employer->update(['is_verified' => false]);
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
}