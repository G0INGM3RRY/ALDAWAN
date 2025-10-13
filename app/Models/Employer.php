<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employer extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'street',
        'barangay',
        'municipality',
        'province',
        'company_logo',
        'employer_type',
        // New fields
        'company_type_id',
        'company_description',
        'website_url',
        'linkedin_url',
        'company_size_min',
        'company_size_max',
        'founded_year',
        'is_verified'
    ];

    protected $casts = [
        'founded_year' => 'integer',
        'company_size_min' => 'integer',
        'company_size_max' => 'integer',
        'is_verified' => 'boolean',
    ];

    /**
     * The user this employer profile belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The company type of this employer
     */
    public function companyType(): BelongsTo
    {
        return $this->belongsTo(CompanyType::class);
    }

    /**
     * The job listings posted by this employer
     */
    public function jobListings(): HasMany
    {
        return $this->hasMany(Jobs::class, 'company_id', 'user_id');
    }

    /**
     * The company verification record
     */
    public function verification(): HasOne
    {
        return $this->hasOne(CompanyVerification::class);
    }

    /**
     * Get full address
     */
    public function getFullAddressAttribute(): string
    {
        return trim(implode(', ', array_filter([
            $this->street,
            $this->barangay,
            $this->municipality,
            $this->province
        ])));
    }

    /**
     * Get company size range as string
     */
    public function getCompanySizeRangeAttribute(): ?string
    {
        $min = $this->company_size_min ?? null;
        $max = $this->company_size_max ?? null;
        
        if ($min && $max) {
            return $min . '-' . $max . ' employees';
        } elseif ($min) {
            return $min . '+ employees';
        }
        return null;
    }

    /**
     * Scope for verified employers
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for formal employers
     */
    public function scopeFormal($query)
    {
        return $query->where('employer_type', 'formal');
    }

    /**
     * Scope for informal employers
     */
    public function scopeInformal($query)
    {
        return $query->where('employer_type', 'informal');
    }
}
