<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Classification extends Model
{
    protected $fillable = [
        'name',
        'description',
        'code',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The jobs in this classification
     */
    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Jobs::class, 'job_classifications', 'classification_id', 'job_listing_id')
                    ->withTimestamps();
    }

    /**
     * Scope to get only active classifications
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('name');
    }
}
