<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobs;


class JobController extends Controller
{
    public function index(Request $request)
    {
        // Start with all jobs
        $query = Jobs::with(['user.employerProfile']);
        
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
        
        $jobs = $query->latest()->get();
        
        // Determine which view to use based on the current user's job seeker type
        $user = auth()->user();
        if ($user && $user->jobseekerProfile && $user->jobseekerProfile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.jobs.index', compact('jobs'));
        } else {
            // Default to formal view for formal job seekers or guests
            return view('users.jobseekers.formal.jobs.index', compact('jobs'));
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
