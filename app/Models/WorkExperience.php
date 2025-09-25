<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class WorkExperience extends Model
{
    protected $fillable = [
        'jobseeker_profile_id',
        'job_title',
        'company_name',
        'description',
        'start_date',
        'end_date',
        'is_current'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * The jobseeker profile this experience belongs to
     */
    public function jobseekerProfile(): BelongsTo
    {
        return $this->belongsTo(JobseekerProfile::class);
    }

    /**
     * Get duration in months
     */
    public function getDurationInMonthsAttribute(): int
    {
        $endDate = $this->is_current ? Carbon::now() : $this->end_date;
        return $this->start_date->diffInMonths($endDate);
    }

    /**
     * Scope for current positions
     */
    public function scopeCurrent($query)
    {
        return $query->where('is_current', true);
    }
}
