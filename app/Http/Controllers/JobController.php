<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobs;
use App\Models\Classification;
use App\Services\JobRecommendationService;


class JobController extends Controller
{
    protected $recommendationService;

    public function __construct(JobRecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        
        // If user is authenticated and has profile, use personalized recommendations
        if ($user && $user->jobseekerProfile && !$request->has('disable_matching')) {
            return $this->indexWithRecommendations($request);
        }
        
        // Otherwise, use traditional filtering
        return $this->indexTraditional($request);
    }

    /**
     * Show jobs with personalized recommendations
     */
    private function indexWithRecommendations(Request $request)
    {
        $user = auth()->user();
        
        // Get recommended jobs using the service
        $jobs = $this->recommendationService->getRecommendedJobs($user);
        
        // Apply manual filters if provided
        if ($request->filled('search')) {
            $search = $request->search;
            $jobs = $jobs->filter(function($job) use ($search) {
                return stripos($job->job_title, $search) !== false ||
                       stripos($job->description, $search) !== false ||
                       ($job->user->employerProfile && stripos($job->user->employerProfile->company_name, $search) !== false);
            });
        }
        
        if ($request->filled('location')) {
            $jobs = $jobs->filter(function($job) use ($request) {
                return stripos($job->location, $request->location) !== false;
            });
        }
        
        if ($request->filled('classification')) {
            $jobs = $jobs->filter(function($job) use ($request) {
                return stripos($job->classification, $request->classification) !== false;
            });
        }
        
        if ($request->filled('employment_type')) {
            $jobs = $jobs->filter(function($job) use ($request) {
                return $job->employment_type === $request->employment_type;
            });
        }
        
        if ($request->filled('min_salary')) {
            $jobs = $jobs->filter(function($job) use ($request) {
                return $job->salary >= $request->min_salary;
            });
        }
        
        if ($request->filled('max_salary')) {
            $jobs = $jobs->filter(function($job) use ($request) {
                return $job->salary <= $request->max_salary;
            });
        }
        
        // Apply sorting (default is by match_score, which is already done)
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'salary_high':
                    $jobs = $jobs->sortByDesc('salary');
                    break;
                case 'salary_low':
                    $jobs = $jobs->sortBy('salary');
                    break;
                case 'oldest':
                    $jobs = $jobs->sortBy('created_at');
                    break;
                case 'newest':
                    $jobs = $jobs->sortByDesc('created_at');
                    break;
                // 'relevance' keeps the match_score sorting
            }
        }
        
        // Get all classifications for filter dropdown
        $classifications = Classification::orderBy('name')->get();
        
        // Determine which view to use
        $profile = $user->jobseekerProfile;
        if ($profile && $profile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.jobs.index', compact('jobs', 'classifications'));
        } else {
            return view('users.jobseekers.formal.jobs.index', compact('jobs', 'classifications'));
        }
    }

    /**
     * Traditional job listing (for guests or when matching is disabled)
     */
    private function indexTraditional(Request $request)
    {
        // Start with all jobs
        $query = Jobs::with(['user.employerProfile', 'classifications']);
        
        // Search by keyword (job title, description, company name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhereHas('user.employerProfile', function($q) use ($search) {
                      $q->where('company_name', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'LIKE', "%{$request->location}%");
        }
        
        // Filter by job classification
        if ($request->filled('classification')) {
            $query->where('classification_id', $request->classification);
        }
        
        // Filter by employment type
        if ($request->filled('employment_type')) {
            $query->where('employment_type', $request->employment_type);
        }
        
        // Filter by salary range
        if ($request->filled('min_salary')) {
            $query->where('salary', '>=', $request->min_salary);
        }
        if ($request->filled('max_salary')) {
            $query->where('salary', '<=', $request->max_salary);
        }
        
        // Filter by job type if specified (job-level classification)
        if ($request->has('job_type') && in_array($request->job_type, ['formal', 'informal'])) {
            $query->where('job_type', $request->job_type);
        }
        
        // Backward compatibility: also support employer_type parameter
        if ($request->has('employer_type') && in_array($request->employer_type, ['formal', 'informal'])) {
            $query->where('job_type', $request->employer_type);
        } else {
            // Filter jobs based on current user's job seeker type if no explicit filter is provided
            $user = auth()->user();
            if ($user && $user->jobseekerProfile) {
                $jobSeekerType = $user->jobseekerProfile->job_seeker_type;
                if ($jobSeekerType === 'informal') {
                    $query->where('job_type', 'informal');
                } elseif ($jobSeekerType === 'formal') {
                    $query->where('job_type', 'formal');
                }
                // If no job seeker type is set, show all jobs (for admins or incomplete profiles)
            }
            // For guests (unauthenticated users), show all jobs
        }
        
        // Only show open jobs
        $query->where('status', 'open');
        
        // Sort by
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'salary_high':
                    $query->orderBy('salary', 'desc');
                    break;
                case 'salary_low':
                    $query->orderBy('salary', 'asc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $jobs = $query->get();
        
        // Get all classifications for filter dropdown
        $classifications = Classification::orderBy('name')->get();
        
        // Determine which view to use based on the current user's job seeker type
        $user = auth()->user();
        if ($user && $user->jobseekerProfile && $user->jobseekerProfile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.jobs.index', compact('jobs', 'classifications'));
        } else {
            // Default to formal view for formal job seekers or guests
            return view('users.jobseekers.formal.jobs.index', compact('jobs', 'classifications'));
        }
    }

    public function show($id)
    {
        // Logic to display a single job
        $job = Jobs::with(['user.employerProfile'])->findOrFail($id);
        
        //  job seeker type identification
        $user = auth()->user();
        if ($user && $user->jobseekerProfile && $user->jobseekerProfile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.jobs.show', compact('job'));
        } else {
            // Default to formal view for formal job seekers or guests
            return view('users.jobseekers.formal.jobs.show', compact('job'));
        }
    }
    
    /**
     * Get formal jobs only
     */
    public function formalJobs()
    {
        $jobs = Jobs::with(['user.employerProfile'])
            ->where('job_type', 'formal')
            ->where('status', 'open')
            ->latest()
            ->get();
            
        // Determine which view to use based on the current user's job seeker type
        $user = auth()->user();
        if ($user && $user->jobseekerProfile && $user->jobseekerProfile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.jobs.index', compact('jobs'));
        } else {
            return view('users.jobseekers.formal.jobs.index', compact('jobs'));
        }
    }
    
    /**
     * Get informal jobs only
     */
    public function informalJobs()
    {
        $jobs = Jobs::with(['user.employerProfile'])
            ->where('job_type', 'informal')
            ->where('status', 'open')
            ->latest()
            ->get();
            
        // Determine which view to use based on the current user's job seeker type
        $user = auth()->user();
        if ($user && $user->jobseekerProfile && $user->jobseekerProfile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.jobs.index', compact('jobs'));
        } else {
            return view('users.jobseekers.formal.jobs.index', compact('jobs'));
        }
    }
}
