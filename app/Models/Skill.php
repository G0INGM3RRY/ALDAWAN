<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'is_active',
        'usage_count',
        'is_custom',
        'show_in_list',
        'last_used_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_custom' => 'boolean',
        'show_in_list' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    /**
     * The jobseeker profiles that have this skill
     */
    public function jobseekerProfiles(): BelongsToMany
    {
        return $this->belongsToMany(JobseekerProfile::class, 'jobseeker_skills')
                    ->withPivot('proficiency_level', 'years_experience')
                    ->withTimestamps();
    }

    /**
     * The jobs that require this skill
     */
    public function jobs(): BelongsToMany
    {
        return $this->belongsToMany(Jobs::class, 'job_skills', 'skill_id', 'job_listing_id')
                    ->withPivot('required_level', 'is_required')
                    ->withTimestamps();
    }

    /**
     * Scope to get only active skills
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get skills that should be displayed in lists
     */
    public function scopeDisplayable($query)
    {
        return $query->where('is_active', true)->where('show_in_list', true);
    }

    /**
     * Get limited skills for display in forms (max 20 per category)
     * Prioritizes: usage count, built-in skills, then creation date
     */
    public static function getLimitedSkillsForDisplay($category, $limit = 20)
    {
        // Map new categories to existing ones
        $categories = [];
        if ($category === 'formal') {
            $categories = ['technical', 'soft', 'language'];
        } elseif ($category === 'informal') {
            $categories = ['trade', 'soft'];
        } else {
            $categories = [$category];
        }

        return static::displayable()
            ->whereIn('category', $categories)
            ->orderByRaw('is_custom ASC') // Built-in skills first
            ->orderBy('usage_count', 'desc') // Then by popularity
            ->orderBy('created_at', 'desc') // Then by newest
            ->limit($limit)
            ->get();
    }

    /**
     * Create or get existing skill and manage the display limit
     */
    public static function createOrGetCustomSkill($name, $category)
    {
        // Map formal/informal to actual categories for storage
        $actualCategory = $category;
        if ($category === 'formal') {
            $actualCategory = 'technical'; // Default to technical for formal skills
        } elseif ($category === 'informal') {
            $actualCategory = 'trade'; // Default to trade for informal skills
        }

        // First check if skill already exists in any related category
        $categories = [];
        if ($category === 'formal') {
            $categories = ['technical', 'soft', 'language'];
        } elseif ($category === 'informal') {
            $categories = ['trade', 'soft'];
        } else {
            $categories = [$actualCategory];
        }

        $existingSkill = static::where('name', $name)
            ->whereIn('category', $categories)
            ->first();

        if ($existingSkill) {
            // Update usage and ensure it's shown in list
            $existingSkill->increment('usage_count');
            $existingSkill->update([
                'show_in_list' => true,
                'last_used_at' => now()
            ]);
            return $existingSkill;
        }

        // Create new custom skill
        $newSkill = static::create([
            'name' => $name,
            'category' => $actualCategory,
            'is_active' => true,
            'is_custom' => true,
            'show_in_list' => true,
            'usage_count' => 1,
            'last_used_at' => now()
        ]);

        // Manage display limit - hide least used custom skills if over limit
        static::manageDisplayLimit($category);

        return $newSkill;
    }

    /**
     * Manage the display limit for a category
     * Keep only top 20 skills visible, hide others
     */
    private static function manageDisplayLimit($category, $limit = 20)
    {
        // Map new categories to existing ones
        $categories = [];
        if ($category === 'formal') {
            $categories = ['technical', 'soft', 'language'];
        } elseif ($category === 'informal') {
            $categories = ['trade', 'soft'];
        } else {
            $categories = [$category];
        }

        $totalDisplayed = static::displayable()
            ->whereIn('category', $categories)
            ->count();

        if ($totalDisplayed > $limit) {
            // Get all skills, then select which ones to hide
            $allSkills = static::displayable()
                ->whereIn('category', $categories)
                ->where('is_custom', true) // Only hide custom skills
                ->orderBy('usage_count', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();

            // Hide excess skills (skip the first $limit skills, hide the rest)
            $skillsToHide = $allSkills->skip($limit);
            
            foreach ($skillsToHide as $skill) {
                $skill->update(['show_in_list' => false]);
            }
        }
    }

    /**
     * Increment usage count when skill is used
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
        $this->update([
            'last_used_at' => now(),
            'show_in_list' => true // Re-show if it was hidden
        ]);

        // Re-manage display limit after usage update
        static::manageDisplayLimit($this->category);
    }
}
