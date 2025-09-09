<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobs;
use App\Models\JobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    /**
     * Show application form based on jobseeker type
     */
    public function quickApply(Jobs $job)
    {
        // Check if user is authenticated and is a jobseeker
        if (!Auth::check() || Auth::user()->role !== 'seeker') {
            return redirect()->route('login')->with('error', 'Please login as a jobseeker to apply.');
        }
        
        // Check if job is still open
        if ($job->status !== 'open') {
            return back()->with('error', 'This job is no longer accepting applications.');
        }
        
        // Check if user has already applied
        if ($job->hasAppliedBy(Auth::id())) {
            return back()->with('info', 'You have already applied for this job.');
        }
        
        // Check if user has completed their profile
        $user = Auth::user();
        $profile = $user->jobseekerProfile;
        if (!$profile) {
            return redirect()->route('jobseekers.complete')
                ->with('error', 'Please complete your profile before applying for jobs.');
        }
        
        // Route to appropriate application form based on jobseeker type
        if ($profile && $profile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.jobs.apply', compact('job', 'user'));
        } else {
            return view('users.jobseekers.formal.jobs.apply', compact('job', 'user'));
        }
    }
    
    /**
     * Store job application with detailed form
     */
    public function store(Request $request, Jobs $job)
    {
        // Check if user is authenticated and is a jobseeker
        if (!Auth::check() || Auth::user()->role !== 'seeker') {
            return redirect()->route('login')->with('error', 'Please login as a jobseeker to apply.');
        }
        
        // Check if job is still open
        if ($job->status !== 'open') {
            return back()->with('error', 'This job is no longer accepting applications.');
        }
        
        // Check if user has already applied
        if ($job->hasAppliedBy(Auth::id())) {
            return back()->with('info', 'You have already applied for this job.');
        }
        
        // Validate request
        $validated = $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'additional_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'quick_apply' => 'nullable|boolean'
        ]);
        
        // Handle quick apply - skip file uploads if quick apply is checked
        if ($request->has('quick_apply') && $request->quick_apply) {
            // Create basic application
            $application = JobApplication::create([
                'user_id' => Auth::id(),
                'job_id' => $job->id,
                'cover_letter' => $validated['cover_letter'] ?? null,
                'applied_at' => now(),
                'status' => 'pending'
            ]);
        } else {
            // Handle file uploads for detailed application
            $resumePath = null;
            if ($request->hasFile('resume')) {
                $resumePath = $request->file('resume')->store('resumes', 'public');
            }
            
            $additionalPaths = [];
            if ($request->hasFile('additional_documents')) {
                foreach ($request->file('additional_documents') as $file) {
                    $additionalPaths[] = $file->store('documents', 'public');
                }
            }
            
            // Create application with files
            $application = JobApplication::create([
                'user_id' => Auth::id(),
                'job_id' => $job->id,
                'cover_letter' => $validated['cover_letter'] ?? null,
                'resume_file_path' => $resumePath,
                'additional_documents' => !empty($additionalPaths) ? $additionalPaths : null,
                'applied_at' => now(),
                'status' => 'pending'
            ]);
        }
        
        // Route back to appropriate view
        $user = Auth::user();
        $profile = $user->jobseekerProfile;
        
        if ($profile && $profile->job_seeker_type === 'informal') {
            return redirect()->route('jobs.show', $job->id)
                ->with('success', 'Your application for this gig has been submitted successfully!');
        } else {
            return redirect()->route('jobs.show', $job->id)
                ->with('success', 'Your application has been submitted successfully!');
        }
    }

    /**
     * Quick apply without form (for direct applications)
     */
    public function quickStore(Jobs $job)
    {
        // Check if user is authenticated and is a jobseeker
        if (!Auth::check() || Auth::user()->role !== 'seeker') {
            return redirect()->route('login')->with('error', 'Please login as a jobseeker to apply.');
        }
        
        // Check if job is still open
        if ($job->status !== 'open') {
            return back()->with('error', 'This job is no longer accepting applications.');
        }
        
        // Check if user has already applied
        if ($job->hasAppliedBy(Auth::id())) {
            return back()->with('info', 'You have already applied for this job.');
        }
        
        // Check if user has completed their profile
        $profile = Auth::user()->jobseekerProfile;
        if (!$profile) {
            return redirect()->route('jobseekers.complete')
                ->with('error', 'Please complete your profile before applying for jobs.');
        }
        
        // Create basic application
        $application = JobApplication::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'applied_at' => now(),
            'status' => 'pending'
        ]);
        
        return redirect()->route('jobs.show', $job->id)
            ->with('success', 'Your application has been submitted successfully! You can add a cover letter and resume later.');
    }
    
    /**
     * Show jobseeker's applications
     */
    public function myApplications()
    {
        $user = Auth::user();
        $applications = $user->jobApplications()
            ->with(['job', 'job.user.employerProfile'])
            ->orderBy('applied_at', 'desc')
            ->paginate(10);
        
        // Route to appropriate applications view based on job seeker type
        $profile = $user->jobseekerProfile;
        if ($profile && $profile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.applications.index', compact('applications', 'user'));
        } else {
            return view('users.jobseekers.formal.applications.index', compact('applications', 'user'));
        }
    }
    
    /**
     * Show single application details (editable form view)
     */
    public function show(JobApplication $application)
    {
        // Check if user owns this application
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        $user = Auth::user();
        $job = $application->job;
        $application->load(['job', 'job.user.employerProfile']);
        
        // Route to appropriate application form view with edit mode
        $profile = $user->jobseekerProfile;
        if ($profile && $profile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.jobs.apply', compact('application', 'job', 'user'))->with('editMode', true);
        } else {
            return view('users.jobseekers.formal.jobs.apply', compact('application', 'job', 'user'))->with('editMode', true);
        }
    }
    
    /**
     * Update an existing application
     */
    public function update(Request $request, JobApplication $application)
    {
        // Check if user owns this application
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        // Check if application can still be edited (only pending applications)
        if ($application->status !== 'pending') {
            return back()->with('error', 'Applications that are no longer pending cannot be edited.');
        }
        
        // Validate request
        $validated = $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'resume' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'additional_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
            'quick_apply' => 'nullable|boolean'
        ]);
        
        // Handle quick apply - clear files if quick apply is checked
        if ($request->has('quick_apply') && $request->quick_apply) {
            $application->update([
                'cover_letter' => $validated['cover_letter'] ?? null,
                'resume_file_path' => null,
                'additional_documents' => null,
            ]);
        } else {
            // Handle file uploads for detailed application
            $updateData = [
                'cover_letter' => $validated['cover_letter'] ?? null,
            ];
            
            // Handle resume upload
            if ($request->hasFile('resume')) {
                // Delete old resume if exists
                if ($application->resume_file_path) {
                    Storage::disk('public')->delete($application->resume_file_path);
                }
                $updateData['resume_file_path'] = $request->file('resume')->store('resumes', 'public');
            }
            
            // Handle additional documents
            if ($request->hasFile('additional_documents')) {
                // Delete old documents if exists
                if ($application->additional_documents) {
                    foreach ($application->additional_documents as $oldDoc) {
                        Storage::disk('public')->delete($oldDoc);
                    }
                }
                
                $additionalPaths = [];
                foreach ($request->file('additional_documents') as $file) {
                    $additionalPaths[] = $file->store('documents', 'public');
                }
                $updateData['additional_documents'] = $additionalPaths;
            }
            
            $application->update($updateData);
        }
        
        // Route back to applications list
        return redirect()->route('jobseekers.applications')
            ->with('success', 'Your application has been updated successfully!');
    }
    
    /**
     * Withdraw an application
     */
    public function withdraw(JobApplication $application)
    {
        // Check if user owns this application
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        // Check if application can be withdrawn
        if (!$application->canBeWithdrawn()) {
            return back()->with('error', 'This application cannot be withdrawn.');
        }
        
        $application->delete();
        
        return back()->with('success', 'Application withdrawn successfully.');
    }
}
