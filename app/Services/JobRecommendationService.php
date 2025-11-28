<?php

namespace App\Services;

use App\Models\Jobs;
use App\Models\User;
use Illuminate\Support\Collection;

class JobRecommendationService
{
    /**
     * Get recommended jobs for a jobseeker based on their profile and preferences
     * 
     * @param User $user
     * @param int $limit
     * @return Collection
     */
    public function getRecommendedJobs(User $user, int $limit = null): Collection
    {
        $profile = $user->jobseekerProfile;
        
        if (!$profile) {
            return collect();
        }

        // Get all open jobs matching the jobseeker type (formal/informal)
        $jobs = Jobs::with(['user.employerProfile', 'classifications', 'requiredSkills'])
            ->where('status', 'open')
            ->where('job_type', $profile->job_seeker_type)
            ->get();

        // Calculate match score for each job
        $scoredJobs = $jobs->map(function ($job) use ($user, $profile) {
            $score = $this->calculateMatchScore($job, $user, $profile);
            $job->match_score = $score;
            return $job;
        });

        // Sort by match score (highest first)
        $recommendedJobs = $scoredJobs->sortByDesc('match_score');

        // Return limited results if specified
        return $limit ? $recommendedJobs->take($limit) : $recommendedJobs;
    }

    /**
     * Calculate match score between a job and jobseeker
     * Total: 100 points (converted to percentage)
     * 
     * @param Jobs $job
     * @param User $user
     * @param mixed $profile
     * @return float
     */
    private function calculateMatchScore(Jobs $job, User $user, $profile): float
    {
        $score = 0;
        $maxScore = 0;

        // 1. Skills Match (40 points max) - Highest weight for technical fit
        $maxScore += 40;
        $skillScore = $this->calculateSkillsMatch($job, $profile);  // Returns 0-40
        $score += $skillScore;

        // 2. Job Preferences Match (30 points max) - Job title, classification, employment type
        $maxScore += 30;
        $preferenceScore = $this->calculatePreferencesMatch($job, $user);  // Returns 0-30
        $score += $preferenceScore;

        // 3. Location Match (15 points max) - Proximity or remote work
        $maxScore += 15;
        $locationScore = $this->calculateLocationMatch($job, $profile);  // Returns 0-15
        $score += $locationScore;

        // 4. Salary Match (10 points max) - Fit within preferred range
        $maxScore += 10;
        $salaryScore = $this->calculateSalaryMatch($job, $user);  // Returns 0-10
        $score += $salaryScore;

        // 5. Education Level Match (5 points max) - Meets minimum requirements
        $maxScore += 5;
        $educationScore = $this->calculateEducationMatch($job, $profile);  // Returns 0-5
        $score += $educationScore;

        // Convert to percentage (0-100): (total_score / 100) * 100 = percentage
        return ($score / $maxScore) * 100;
    }

    /**
     * Calculate skills match score (0-40 points)
     * Formula: (matching_skills / required_skills) × 40
     */
    private function calculateSkillsMatch(Jobs $job, $profile): float
    {
        $jobseekerSkills = $profile->skills->pluck('id')->toArray();
        $requiredSkills = $job->requiredSkills->pluck('id')->toArray();

        if (empty($requiredSkills)) {
            return 20;  // No requirements = neutral score (50% of max)
        }

        if (empty($jobseekerSkills)) {
            return 0;  // No skills = 0 points
        }

        // Calculate percentage of required skills that jobseeker has
        $matchingSkills = array_intersect($jobseekerSkills, $requiredSkills);
        $matchPercentage = count($matchingSkills) / count($requiredSkills);  // e.g., 3/5 = 0.6

        return $matchPercentage * 40;  // e.g., 0.6 × 40 = 24 points
    }

    /**
     * Calculate job preferences match score (0-30 points)
     * Breakdown: Title similarity (10) + Classification (10) + Employment type (10)
     */
    private function calculatePreferencesMatch(Jobs $job, User $user): float
    {
        $preferences = $user->jobPreferences;
        
        if (!$preferences || $preferences->isEmpty()) {
            return 15;  // No preferences = neutral score (50% of max)
        }

        $totalScore = 0;
        $matchCount = 0;

        foreach ($preferences as $preference) {
            $preferenceScore = 0;

            // Job title similarity (10 points) - Uses Levenshtein distance
            if ($preference->preferred_job_title) {
                $similarity = $this->calculateStringSimilarity(
                    strtolower($preference->preferred_job_title),
                    strtolower($job->job_title)
                );  // Returns 0-1 (e.g., 0.8 for 80% similar)
                $preferenceScore += $similarity * 10;  // e.g., 0.8 × 10 = 8 points
            }

            // Classification match (10 points) - Exact or contains
            if ($preference->preferred_classification) {
                if (stripos($job->classification, $preference->preferred_classification) !== false) {
                    $preferenceScore += 10;  // Full 10 points for match
                }
            }

            // Employment type match (10 points) - Exact match required
            if ($preference->preferred_employment_type) {
                if ($job->employment_type === $preference->preferred_employment_type) {
                    $preferenceScore += 10;  // Full 10 points for exact match
                }
            }

            $totalScore += $preferenceScore;  // Sum all preference scores
            $matchCount++;
        }

        // Average score across all preferences, capped at 30 points
        return $matchCount > 0 ? min(30, $totalScore / $matchCount) : 15;
    }

    /**
     * Calculate location match score (0-15 points)
     * Priority: Remote (15) > Municipality (15) > Barangay (12) > Province (10) > Preferred (8)
     */
    private function calculateLocationMatch(Jobs $job, $profile): float
    {
        // Check if job offers remote work
        if ($job->remote_work_available) {
            return 15;  // Perfect match - location irrelevant
        }

        $jobLocation = strtolower($job->location);
        
        // Check municipality match (highest priority for on-site)
        if ($profile->municipality && stripos($jobLocation, strtolower($profile->municipality)) !== false) {
            return 15;  // Same city/municipality
        }

        // Check province match (broader area)
        if ($profile->province && stripos($jobLocation, strtolower($profile->province)) !== false) {
            return 10;  // Same province but different municipality
        }

        // Check barangay match (very specific locality)
        if ($profile->barangay && stripos($jobLocation, strtolower($profile->barangay)) !== false) {
            return 12;  // Same barangay
        }

        // Check job preferences location (user-specified preference)
        $user = $profile->user;
        if ($user->jobPreferences) {
            foreach ($user->jobPreferences as $preference) {
                if ($preference->preferred_location && 
                    stripos($jobLocation, strtolower($preference->preferred_location)) !== false) {
                    return 8;  // Matches user's preferred location
                }
            }
        }

        return 0;  // No location match
    }

    /**
     * Calculate salary match score (0-10 points)
     * Perfect (10) = within range | Good (7) = above min | Partial = proportional
     */
    private function calculateSalaryMatch(Jobs $job, User $user): float
    {
        $preferences = $user->jobPreferences;
        
        if (!$preferences || $preferences->isEmpty()) {
            return 5;  // No preference = neutral (50% of max)
        }

        $bestMatch = 0;

        foreach ($preferences as $preference) {
            $score = 0;

            // If job salary is within preferred range
            if ($preference->min_salary && $preference->max_salary) {
                if ($job->salary >= $preference->min_salary && $job->salary <= $preference->max_salary) {
                    $score = 10;  // Perfect: e.g., wants 20k-30k, job pays 25k
                } elseif ($job->salary >= $preference->min_salary) {
                    $score = 7;  // Good: e.g., wants 20k-30k, job pays 35k
                } else {
                    // Below minimum - proportional penalty
                    $difference = abs($job->salary - $preference->min_salary);
                    $score = max(0, 5 - ($difference / $preference->min_salary) * 5);  // Decreases as gap widens
                }
            } elseif ($preference->min_salary) {
                // Only minimum specified
                if ($job->salary >= $preference->min_salary) {
                    $score = 10;  // Meets minimum requirement
                } else {
                    $score = ($job->salary / $preference->min_salary) * 10;  // Proportional: 15k/20k × 10 = 7.5 points
                }
            }

            $bestMatch = max($bestMatch, $score);  // Take highest score from all preferences
        }

        return $bestMatch;
    }

    /**
     * Calculate education level match score (0-5 points)
     * Full credit if meets/exceeds | Partial credit based on gap
     */
    private function calculateEducationMatch(Jobs $job, $profile): float
    {
        if (!$job->minimum_education_level_id) {
            return 5;  // No requirement = full points
        }

        if (!$profile->education_level_id) {
            return 0;  // No education data = 0 points
        }

        // If jobseeker's education level meets or exceeds requirement
        if ($profile->education_level_id >= $job->minimum_education_level_id) {
            return 5;  // e.g., has Bachelor's (4), needs High School (2) → 5 points
        }

        // Partial credit based on how close they are
        $difference = abs($profile->education_level_id - $job->minimum_education_level_id);
        return max(0, 5 - $difference);  // e.g., has level 3, needs 5 → gap=2 → 5-2=3 points
    }

    /**
     * Calculate string similarity (0-1)
     * Uses Levenshtein distance: measures character edits needed to transform one string to another
     */
    private function calculateStringSimilarity(string $str1, string $str2): float
    {
        // Use Levenshtein distance for similarity
        $maxLen = max(strlen($str1), strlen($str2));
        
        if ($maxLen === 0) {
            return 1.0;  // Empty strings are identical
        }

        $distance = levenshtein($str1, $str2);  // e.g., "developer" vs "developr" = 1 edit
        return 1 - ($distance / $maxLen);  // e.g., 1 - (1/9) = 0.889 (88.9% similar)
    }

    /**
     * Get top matched jobs for dashboard "Recommended for You" section
     * 
     * @param User $user
     * @param int $limit
     * @return Collection
     */
    public function getTopRecommendations(User $user, int $limit = 5): Collection
    {
        $recommendations = $this->getRecommendedJobs($user, $limit * 2);
        
        // Filter to only show jobs with decent match (>= 50% match score)
        return $recommendations->filter(function ($job) {
            return $job->match_score >= 50;
        })->take($limit);
    }
}
