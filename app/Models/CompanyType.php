<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CompanyType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The employers of this company type
     */
    public function employers(): HasMany
    {
        return $this->hasMany(Employer::class);
    }

    /**
     * Scope to get only active company types
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('name');
    }
}
