<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InformalEmployerVerification extends Model
{
    protected $fillable = [
        'employer_id',
        'status',
        'valid_id_path',
        'proof_of_address_path',
        'barangay_clearance_path',
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
     * The admin user who verified this employer
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
            'verification_notes' => $notes,
            'rejection_reason' => null,
        ]);
    }

    /**
     * Mark as rejected
     */
    public function reject(User $admin, string $reason, ?string $notes = null): void
    {
        $this->update([
            'status' => 'rejected',
            'verified_by' => $admin->id,
            'verified_at' => now(),
            'rejection_reason' => $reason,
            'verification_notes' => $notes,
        ]);
    }

    /**
     * Request more information
     */
    public function requireInfo(User $admin, string $notes): void
    {
        $this->update([
            'status' => 'requires_info',
            'verified_by' => $admin->id,
            'verification_notes' => $notes,
        ]);
    }
}
