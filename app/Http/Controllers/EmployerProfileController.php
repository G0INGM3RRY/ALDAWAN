<?php

namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use App\Models\JobseekerProfile;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Employer;
use App\Models\Jobs;
use App\Models\User;

class EmployerProfileController extends Controller
{


    public function complete(){
        $user = Auth::user();
        $profile= $user->employerProfile;
        return view('users.employers.complete', compact('user', 'profile'));
    }
   
    public function update(Request $request)
    { 
        //Validate and save employer profile
        $user = Auth::user();

        $request->validate([
            'company_name' => 'required|string|max:255',
            'street' => 'nullable|string|max:255',
            'barangay' => 'nullable|string|max:255',
            'municipality' => 'nullable|string|max:255',
            'province' => 'nullable|string|max:255',
            'company_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = $request->only(['company_name', 'street', 'barangay', 'municipality', 'province']);
        
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
        $profile= $user->employerProfile;
        return view('users.employers.edit', compact('user', 'profile'));
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
        return view('users.employers.jobs.index', compact('user', 'jobs'));
    }

    public function createJob()
    {
        $user = Auth::user();
        return view('users.employers.create', compact('user'));
    }

    public function storeJob(Request $request)
    {
        $user = Auth::user();

        // Validate the form data
        $request->validate([
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|in:full-time,part-time,contract,freelance,internship',
            'classification' => 'required|string|max:255',
        ]);

        // Create the job posting
        $user->jobs()->create([
            'job_title' => $request->job_title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'salary' => $request->salary,
            'location' => $request->location,
            'employment_type' => $request->employment_type,
            'classification' => $request->classification,
            'status' => 'open',
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
        
        return view('users.employers.jobs.edit', compact('user', 'job'));
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
            'employment_type' => 'required|in:full-time,part-time,contract,freelance,internship',
            'classification' => 'required|string|max:255',
            'status' => 'required|in:open,closed,filled',
        ]);

        // Update the job posting
        $job->update([
            'job_title' => $request->job_title,
            'description' => $request->description,
            'requirements' => $request->requirements,
            'salary' => $request->salary,
            'location' => $request->location,
            'employment_type' => $request->employment_type,
            'classification' => $request->classification,
            'status' => $request->status,
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

}
