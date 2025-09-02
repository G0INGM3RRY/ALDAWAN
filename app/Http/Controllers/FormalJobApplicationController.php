<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobs;
use App\Models\FormalJobApplication;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FormalJobApplicationController extends Controller
{
    /**
     * Quick apply - Create application without form (for now)
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
        $profile = Auth::user()->jobseekerProfile;
        if (!$profile) {
            return redirect()->route('jobseekers.complete')
                ->with('error', 'Please complete your profile before applying for jobs.');
        }
        
        // Create basic application
        $application = FormalJobApplication::create([
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'applied_at' => now(),
            'status' => 'pending'
        ]);
        
        return redirect()->route('jobs.show', $job->id)
            ->with('success', 'Your application has been submitted successfully! You can add a cover letter and resume later.');
    }
    
    /**
     * Store a new job application
     */
    public function store(Request $request, Jobs $job)
    {
        // Validation
        $request->validate([
            'cover_letter' => 'nullable|string|max:2000',
            'resume_file' => 'nullable|file|mimes:pdf,doc,docx|max:2048', // 2MB max
            'additional_documents.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048'
        ]);
        
        // Check prerequisites again
        if (!Auth::check() || Auth::user()->role !== 'seeker') {
            return redirect()->route('login');
        }
        
        if ($job->status !== 'open') {
            return back()->with('error', 'This job is no longer accepting applications.');
        }
        
        if ($job->hasAppliedBy(Auth::id())) {
            return back()->with('error', 'You have already applied for this job.');
        }
        
        $applicationData = [
            'user_id' => Auth::id(),
            'job_id' => $job->id,
            'cover_letter' => $request->cover_letter,
            'applied_at' => now(),
        ];
        
        // Handle resume file upload
        if ($request->hasFile('resume_file')) {
            $resumePath = $request->file('resume_file')->store('formal-applications/resumes', 'public');
            $applicationData['resume_file_path'] = $resumePath;
        }
        
        // Handle additional documents
        $additionalDocs = [];
        if ($request->hasFile('additional_documents')) {
            foreach ($request->file('additional_documents') as $index => $file) {
                $path = $file->store('formal-applications/documents', 'public');
                $additionalDocs[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'type' => $file->getClientMimeType()
                ];
            }
            $applicationData['additional_documents'] = $additionalDocs;
        }
        
        // Create the application
        $application = FormalJobApplication::create($applicationData);
        
        return redirect()->route('jobs.show', $job->id)
            ->with('success', 'Your application has been submitted successfully!');
    }
    
    /**
     * Show jobseeker's applications
     */
    public function myApplications()
    {
        $applications = Auth::user()->formalJobApplications()
            ->with(['job', 'job.user.employerProfile'])
            ->orderBy('applied_at', 'desc')
            ->paginate(10);
            
        return view('users.jobseekers.applications.index', compact('applications'));
    }
    
    /**
     * Show single application details
     */
    public function show(FormalJobApplication $application)
    {
        // Check if user owns this application
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        return view('users.jobseekers.applications.show', compact('application'));
    }
    
    /**
     * Withdraw an application
     */
    public function withdraw(FormalJobApplication $application)
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
