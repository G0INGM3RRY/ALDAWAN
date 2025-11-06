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

        // 1. Skills Match (40 points max)
        $maxScore += 40;
        $skillScore = $this->calculateSkillsMatch($job, $profile);
        $score += $skillScore;

        // 2. Job Preferences Match (30 points max)
        $maxScore += 30;
        $preferenceScore = $this->calculatePreferencesMatch($job, $user);
        $score += $preferenceScore;

        // 3. Location Match (15 points max)
        $maxScore += 15;
        $locationScore = $this->calculateLocationMatch($job, $profile);
        $score += $locationScore;

        // 4. Salary Match (10 points max)
        $maxScore += 10;
        $salaryScore = $this->calculateSalaryMatch($job, $user);
        $score += $salaryScore;

        // 5. Education Level Match (5 points max)
        $maxScore += 5;
        $educationScore = $this->calculateEducationMatch($job, $profile);
        $score += $educationScore;

        // Convert to percentage (0-100)
        return ($score / $maxScore) * 100;
    }

    /**
     * Calculate skills match score (0-40 points)
     */
    private function calculateSkillsMatch(Jobs $job, $profile): float
    {
        $jobseekerSkills = $profile->skills->pluck('id')->toArray();
        $requiredSkills = $job->requiredSkills->pluck('id')->toArray();

        if (empty($requiredSkills)) {
            // If job has no specific skill requirements, give partial credit
            return 20;
        }

        if (empty($jobseekerSkills)) {
            return 0;
        }

        // Calculate percentage of required skills that jobseeker has
        $matchingSkills = array_intersect($jobseekerSkills, $requiredSkills);
        $matchPercentage = count($matchingSkills) / count($requiredSkills);

        return $matchPercentage * 40;
    }

    /**
     * Calculate job preferences match score (0-30 points)
     */
    private function calculatePreferencesMatch(Jobs $job, User $user): float
    {
        $preferences = $user->jobPreferences;
        
        if (!$preferences || $preferences->isEmpty()) {
            return 15; // Neutral score if no preferences set
        }

        $totalScore = 0;
        $matchCount = 0;

        foreach ($preferences as $preference) {
            $preferenceScore = 0;

            // Job title similarity (10 points)
            if ($preference->preferred_job_title) {
                $similarity = $this->calculateStringSimilarity(
                    strtolower($preference->preferred_job_title),
                    strtolower($job->job_title)
                );
                $preferenceScore += $similarity * 10;
            }

            // Classification match (10 points)
            if ($preference->preferred_classification) {
                if (stripos($job->classification, $preference->preferred_classification) !== false) {
                    $preferenceScore += 10;
                }
            }

            // Employment type match (10 points)
            if ($preference->preferred_employment_type) {
                if ($job->employment_type === $preference->preferred_employment_type) {
                    $preferenceScore += 10;
                }
            }

            $totalScore += $preferenceScore;
            $matchCount++;
        }

        // Average score across all preferences, max 30 points
        return $matchCount > 0 ? min(30, $totalScore / $matchCount) : 15;
    }

    /**
     * Calculate location match score (0-15 points)
     */
    private function calculateLocationMatch(Jobs $job, $profile): float
    {
        // Check if job offers remote work
        if ($job->remote_work_available) {
            return 15; // Perfect match for remote jobs
        }

        $jobLocation = strtolower($job->location);
        
        // Check municipality match
        if ($profile->municipality && stripos($jobLocation, strtolower($profile->municipality)) !== false) {
            return 15;
        }

        // Check province match
        if ($profile->province && stripos($jobLocation, strtolower($profile->province)) !== false) {
            return 10;
        }

        // Check barangay match
        if ($profile->barangay && stripos($jobLocation, strtolower($profile->barangay)) !== false) {
            return 12;
        }

        // Check job preferences location
        $user = $profile->user;
        if ($user->jobPreferences) {
            foreach ($user->jobPreferences as $preference) {
                if ($preference->preferred_location && 
                    stripos($jobLocation, strtolower($preference->preferred_location)) !== false) {
                    return 8;
                }
            }
        }

        return 0;
    }

    /**
     * Calculate salary match score (0-10 points)
     */
    private function calculateSalaryMatch(Jobs $job, User $user): float
    {
        $preferences = $user->jobPreferences;
        
        if (!$preferences || $preferences->isEmpty()) {
            return 5; // Neutral score if no preferences
        }

        $bestMatch = 0;

        foreach ($preferences as $preference) {
            $score = 0;

            // If job salary is within preferred range
            if ($preference->min_salary && $preference->max_salary) {
                if ($job->salary >= $preference->min_salary && $job->salary <= $preference->max_salary) {
                    $score = 10; // Perfect match
                } elseif ($job->salary >= $preference->min_salary) {
                    $score = 7; // Above minimum
                } else {
                    // Below minimum - calculate how close
                    $difference = abs($job->salary - $preference->min_salary);
                    $score = max(0, 5 - ($difference / $preference->min_salary) * 5);
                }
            } elseif ($preference->min_salary) {
                // Only minimum specified
                if ($job->salary >= $preference->min_salary) {
                    $score = 10;
                } else {
                    $score = ($job->salary / $preference->min_salary) * 10;
                }
            }

            $bestMatch = max($bestMatch, $score);
        }

        return $bestMatch;
    }

    /**
     * Calculate education level match score (0-5 points)
     */
    private function calculateEducationMatch(Jobs $job, $profile): float
    {
        if (!$job->minimum_education_level_id) {
            return 5; // No requirement, full points
        }

        if (!$profile->education_level_id) {
            return 0; // No education info from jobseeker
        }

        // If jobseeker's education level meets or exceeds requirement
        if ($profile->education_level_id >= $job->minimum_education_level_id) {
            return 5;
        }

        // Partial credit based on how close they are
        $difference = abs($profile->education_level_id - $job->minimum_education_level_id);
        return max(0, 5 - $difference);
    }

    /**
     * Calculate string similarity (0-1)
     */
    private function calculateStringSimilarity(string $str1, string $str2): float
    {
        // Use Levenshtein distance for similarity
        $maxLen = max(strlen($str1), strlen($str2));
        
        if ($maxLen === 0) {
            return 1.0;
        }

        $distance = levenshtein($str1, $str2);
        return 1 - ($distance / $maxLen);
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
