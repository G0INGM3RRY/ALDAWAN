<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use App\Models\JobseekerProfile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Employer;
use App\Models\Jobs;
use App\Models\JobApplication;
use App\Models\User;

class EmployerProfileController extends Controller
{
    /**
     * Get the appropriate view path based on employer type
     */
    private function getViewPath($basePath)
    {
        $user = Auth::user();
        if ($user && $user->employerProfile && $user->employerProfile->employer_type === 'informal') {
            return "users.employers.informal.{$basePath}";
        } else {
            return "users.employers.formal.{$basePath}";
        }
    }

    public function complete(){
        $user = Auth::user();
        $profile = $user->employerProfile;
        $companyTypes = \App\Models\CompanyType::where('is_active', true)->get();
        return view($this->getViewPath('complete'), compact('user', 'profile', 'companyTypes'));
    }

    public function show()
    {
        $user = Auth::user();
        $profile = $user->employerProfile;
        return view($this->getViewPath('show'), compact('user', 'profile'));
    }
   
    public function update(Request $request)
    { 
        //Validate and save employer profile
        $user = Auth::user();
        $existingProfile = $user->employerProfile;

        // Prevent employer type changes after initial setup
        if ($existingProfile && $existingProfile->employer_type) {
            $requiredType = $existingProfile->employer_type;
            
            // If user is trying to change type, reject with error
            if ($request->employer_type !== $requiredType) {
                return redirect()->back()->withErrors([
                    'employer_type' => 'Employer type cannot be changed after registration.'
                ])->withInput();
            }
            
            // Validate with locked type and different rules based on employer type
            $baseValidation = [
                'company_name' => 'required|string|max:255',
                'employer_type' => 'required|in:' . $requiredType,
                'street' => 'nullable|string|max:255',
                'barangay' => 'nullable|string|max:255',
                'municipality' => 'nullable|string|max:255',
                'province' => 'nullable|string|max:255',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'company_type_id' => 'nullable|exists:company_types,id',
                'company_description' => 'nullable|string|max:1000',
                'website_url' => 'nullable|url|max:255',
                'linkedin_url' => 'nullable|url|max:255',
            ];
            
            // Add business-specific validation only for formal employers
            if ($requiredType === 'formal') {
                $baseValidation = array_merge($baseValidation, [
                    'company_size_min' => 'nullable|integer|min:1',
                    'company_size_max' => 'nullable|integer|min:1|gte:company_size_min',
                    'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
                ]);
            }
            
            $request->validate($baseValidation);
        } else {
            // First time setup - allow any type
            $baseValidation = [
                'company_name' => 'required|string|max:255',
                'employer_type' => 'required|in:formal,informal',
                'street' => 'nullable|string|max:255',
                'barangay' => 'nullable|string|max:255',
                'municipality' => 'nullable|string|max:255',
                'province' => 'nullable|string|max:255',
                'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'company_description' => 'nullable|string|max:1000',
            ];
            
            // Different validation based on employer type
            if ($request->employer_type === 'formal') {
                // Formal employers need business-related fields
                $baseValidation = array_merge($baseValidation, [
                    'company_type_id' => 'required|exists:company_types,id',
                    'website_url' => 'nullable|url|max:255',
                    'linkedin_url' => 'nullable|url|max:255',
                    'company_size_min' => 'required|integer|min:1',
                    'company_size_max' => 'nullable|integer|min:1|gte:company_size_min',
                    'founded_year' => 'required|integer|min:1800|max:' . date('Y'),
                ]);
            } else {
                // Informal employers (households) - more relaxed requirements
                $baseValidation = array_merge($baseValidation, [
                    'company_type_id' => 'nullable|exists:company_types,id',
                    'website_url' => 'nullable|url|max:255',
                    'linkedin_url' => 'nullable|url|max:255',
                    'company_size_min' => 'nullable|integer|min:1',
                    'company_size_max' => 'nullable|integer|min:1|gte:company_size_min',
                    'founded_year' => 'nullable|integer|min:1800|max:' . date('Y'),
                ]);
            }
            
            $request->validate($baseValidation);
        }

        // Get base data that all employer types have
        $data = $request->only([
            'company_name', 'employer_type', 'street', 'barangay', 'municipality', 'province',
            'company_type_id', 'company_description', 'website_url', 'linkedin_url'
        ]);
        
        // Determine employer type (from request or existing profile)
        $employerType = $request->employer_type ?? ($existingProfile->employer_type ?? 'informal');
        
        // Add business-specific fields only for formal employers
        if ($employerType === 'formal') {
            $businessData = $request->only(['company_size_min', 'company_size_max', 'founded_year']);
            $data = array_merge($data, $businessData);
        } else {
            // For informal employers, set defaults for business fields if they don't exist in form
            $data['company_size_min'] = $request->input('company_size_min', null);
            $data['company_size_max'] = $request->input('company_size_max', null);
            $data['founded_year'] = $request->input('founded_year', null);
        }
        
        // Handle file upload
        if ($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('company_logos', $filename, 'public');
            $data['company_logo'] = $filePath;
        }

        $user->employerProfile()->updateOrCreate(
            ['user_id' => $user->id],
            $data
        );

         return redirect()->route('dashboard')->with('success', 'Profile saved!');
    }

    public function edit()
    {
        $user = Auth::user();
        $profile = $user->employerProfile;
        $companyTypes = \App\Models\CompanyType::where('is_active', true)->get();
        return view($this->getViewPath('edit'), compact('user', 'profile', 'companyTypes'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('users.employers.create', compact('user'));//edit
    }

    public function index()
    {
        $user = Auth::user();
        $jobs = $user->jobs; // Get jobs posted by this employer
        return view($this->getViewPath('jobs.index'), compact('user', 'jobs'));
    }

    public function createJob()
    {
        $user = Auth::user();
        $jobClassifications = \App\Models\Classification::active()->get();
        return view($this->getViewPath('jobs.create'), compact('user', 'jobClassifications'));
    }

    public function storeJob(Request $request)
    {
        $user = Auth::user();

        // Validate the form data - simplified for household employers
        $request->validate([
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'salary' => 'nullable|numeric|min:0',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|in:full_time,part_time,contract,temporary,internship',
            'classification' => 'nullable|string',
            'job_type' => 'nullable|in:formal,informal',
            'positions_available' => 'nullable|integer|min:1',
            'benefits' => 'nullable|string|max:1000',
        ]);

        // Get classification name from select or default
        $classificationName = 'Other';
        if ($request->classification) {
            $classification = \App\Models\Classification::find($request->classification);
            $classificationName = $classification ? $classification->name : 'Other';
        }

        // Determine job type based on employer type
        $jobType = 'informal';
        if ($user->employerProfile && $user->employerProfile->employer_type === 'formal') {
            $jobType = 'formal';
        }

        // Create the job posting
        $user->jobs()->create([
            'job_title' => $request->job_title,
            'description' => $request->description,
            'requirements' => $request->requirements ?? '',
            'salary' => $request->salary ?? 0,
            'location' => $request->location,
            'employment_type' => $request->employment_type,
            'classification' => $classificationName,
            'job_type' => $jobType,
            'status' => 'open',
            'positions_available' => $request->positions_available ?? 1,
            'benefits' => $request->benefits,
        ]);

        return redirect()->route('employers.jobs.index')->with('success', 'Job posted successfully!');
    }

    public function editJob(Jobs $job)
    {
        $user = Auth::user();
        
        // Ensure the job belongs to the authenticated user
        if ($job->company_id !== $user->id) {
            abort(403, 'Unauthorized');
        }
        
        $jobClassifications = \App\Models\Classification::active()->get();
        $disabilities = \App\Models\Disability::active()->get();
        
        return view($this->getViewPath('jobs.edit'), compact('user', 'job', 'jobClassifications', 'disabilities'));
    }

    public function updateJob(Request $request, Jobs $job)
    {
        $user = Auth::user();
        
        // Ensure the job belongs to the authenticated user
        if ($job->company_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Validate the form data
        $request->validate([
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,temporary,internship',
            'classification' => 'required|string|max:255',
            'job_type' => 'required|in:formal,informal',
            'status' => 'required|in:open,closed,filled',
            'disability_restrictions' => 'nullable|array',
            'disability_restrictions.*' => 'exists:disabilities,id',
            'accessibility_notes' => 'nullable|string|max:1000',
        ]);

        // Convert employment_type from hyphen to underscore format for database
        $employmentType = str_replace('-', '_', $request->employment_type);

        // Update the job posting
        $job->update([
            'job_title' => $request->job_title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'salary' => $request->salary,
            'location' => $request->location,
            'employment_type' => $employmentType,
            'classification' => $request->classification,
            'job_type' => $request->job_type,
            'status' => $request->status,
            'disability_restrictions' => $request->disability_restrictions ?: [],
            'accessibility_notes' => $request->accessibility_notes,
        ]);

        return redirect()->route('employers.jobs.index')->with('success', 'Job updated successfully!');
    }

    public function deleteJob(Jobs $job)
    {
        $user = Auth::user();

        // Ensure the job belongs to the authenticated user
        if ($job->company_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Delete the job posting
        $job->delete();

        return redirect()->route('employers.jobs.index')->with('success', 'Job deleted successfully!');
    }

    //added offline
    public function viewapplications(Jobs $job){
        $user = Auth::user();
        
        // Ensure the job belongs to the authenticated employer
        if ($job->company_id !== $user->id) {
            abort(403, 'Unauthorized access to job applications.');
        }
        
        // Fetch applications with related user data and jobseeker profiles
        $applications = $job->applications()
            ->with(['user', 'user.jobseekerProfile'])
            ->orderBy('applied_at', 'desc')
            ->get();
        
        return view($this->getViewPath('viewapplications'), compact('job', 'applications', 'user'));
    }

    // Accept application
    public function acceptApplication(Request $request, JobApplication $application)
    {
        $user = Auth::user();
        
        // Ensure the application belongs to the employer's job
        if ($application->job->company_id !== $user->id) {
            abort(403, 'Unauthorized access to application.');
        }
        
        $updateData = [
            'status' => 'accepted',
            'status_updated_at' => now(),
            'reviewed_at' => now()
        ];
        
        // Add notes if provided
        if ($request->filled('notes')) {
            $updateData['employer_notes'] = $request->input('notes');
        }
        
        $application->update($updateData);
        
        return redirect()->back()->with('success', 'Application accepted successfully.');
    }

    // Reject application
    public function rejectApplication(Request $request, JobApplication $application)
    {
        $user = Auth::user();
        
        // Ensure the application belongs to the employer's job
        if ($application->job->company_id !== $user->id) {
            abort(403, 'Unauthorized access to application.');
        }
        
        $application->update([
            'status' => 'rejected',
            'status_updated_at' => now(),
            'reviewed_at' => now(),
            'rejection_reason' => $request->input('rejection_reason')
        ]);
        
        return redirect()->back()->with('success', 'Application rejected.');
    }

    // View single application
    public function viewApplication(JobApplication $application)
    {
        $user = Auth::user();
        
        // Ensure the application belongs to the employer's job
        if ($application->job->company_id !== $user->id) {
            abort(403, 'Unauthorized access to application.');
        }
        
        $application->load(['user', 'user.jobseekerProfile', 'job']);
        
        // Mark as reviewed if not already reviewed
        if (!$application->reviewed_at && $application->status === 'pending') {
            $application->update([
                'status' => 'under_review',
                'reviewed_at' => now(),
                'status_updated_at' => now()
            ]);
        }
        
        return view($this->getViewPath('application-detail'), compact('application', 'user'));
    }
}
