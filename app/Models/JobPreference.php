<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPreference extends Model
{
    protected $fillable = [
        'user_id',
        'preferred_job_title',
        'preferred_classification',
        'min_salary',
        'max_salary',
        'preferred_location',
        'preferred_employment_type'
    ];

    // Relationship: JobPreference belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
