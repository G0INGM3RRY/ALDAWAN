<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    protected $table = 'job_listings'; // Specify the correct table name
    
    protected $fillable = [
        'job_title',
        'description',
        'company_id',
        'location',
        'salary',
        'employment_type',
        'requirements',
        'posted_at',
        'status',
        'classification'
    ];
    
    // Relationship: Job belongs to a User (employer)
    public function user()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
    
    // Alias for easier access
    public function employer()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}
