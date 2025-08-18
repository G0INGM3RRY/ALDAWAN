<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use App\Models\JobseekerProfile;
use App\Models\User;
use App\Models\JobPreference;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class JobseekerProfileController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        $data = $request->validate([
            'job_seeker_type' => 'required|string|in:formal,informal',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'birthday' => 'nullable|date',
            'sex' => 'nullable|string',
            'photo' => 'nullable|string',
            'civilstatus' => 'nullable|string',
            'street' => 'nullable|string',
            'barangay' => 'nullable|string',
            'municipality' => 'nullable|string',
            'province' => 'nullable|string',
            'religion' => 'nullable|string',
            'contactnumber' => 'nullable|string',
            'email' => 'nullable|string',
            'disability' => 'nullable|array',
            'is_4ps' => 'nullable',
            'employmentstatus' => 'nullable|string',
            'education' => 'nullable|array',
            'work' => 'nullable|array',
            'skills' => 'nullable|array',
            'skills_other' => 'nullable|string',
        ]);

        $profile = JobseekerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = new JobseekerProfile();
            $profile->user_id = $user->id;
        }
        $profile->fill($data);
        $profile->disability = json_encode($request->input('disability', []));
        $profile->education = json_encode($request->input('education', []));
        $profile->work_experience = json_encode($request->input('work', []));
        $skills = $request->input('skills', []);
        $skills_other = $request->input('skills_other', '');
        if ($skills_other) {
            $skills = array_merge($skills, array_map('trim', explode(',', $skills_other)));
        }
        $profile->skills = json_encode($skills);
        $profile->is_4ps = $request->has('is_4ps');
        $profile->save();

        // Handle job preferences
        if ($request->has('job_preferences')) {
            // Delete existing preferences
            $user->jobPreferences()->delete();
            
            // Save new preferences
            foreach ($request->job_preferences as $preference) {
                if (!empty($preference['preferred_job_title']) && !empty($preference['preferred_classification'])) {
                    JobPreference::create([
                        'user_id' => $user->id,
                        'preferred_job_title' => $preference['preferred_job_title'],
                        'preferred_classification' => $preference['preferred_classification'],
                        'min_salary' => $preference['min_salary'] ?? null,
                        'max_salary' => $preference['max_salary'] ?? null,
                        'preferred_location' => $preference['preferred_location'] ?? null,
                        'preferred_employment_type' => $preference['preferred_employment_type'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'Profile saved!');
    }

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->jobseekerProfile; // Get the user's profile data
        $jobPreferences = $user->jobPreferences; // Get user's job preferences
        return view('users.jobseekers.edit', compact('user', 'profile', 'jobPreferences'));
    }

    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user(); // Get the authenticated user

        $data = $request->validate([
            'job_seeker_type' => 'required|string|in:formal,informal',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'birthday' => 'nullable|date',
            'sex' => 'nullable|string',
            'photo' => 'nullable|string',
            'civilstatus' => 'nullable|string',
            'street' => 'nullable|string',
            'barangay' => 'nullable|string',
            'municipality' => 'nullable|string',
            'province' => 'nullable|string',
            'religion' => 'nullable|string',
            'contactnumber' => 'nullable|string',
            'email' => 'nullable|string',
            'disability' => 'nullable|array',
            'is_4ps' => 'nullable',
            'employmentstatus' => 'nullable|string',
            'education' => 'nullable|array',
            'work' => 'nullable|array',
            'skills' => 'nullable|array',
            'skills_other' => 'nullable|string',
        ]);

        $profile = JobseekerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = new JobseekerProfile();
            $profile->user_id = $user->id;
        }
        
        $profile->fill($data);
        $profile->disability = json_encode($request->input('disability', []));
        $profile->education = json_encode($request->input('education', []));
        $profile->work_experience = json_encode($request->input('work', []));
        
        $skills = $request->input('skills', []);
        $skills_other = $request->input('skills_other', '');
        if ($skills_other) {
            $skills = array_merge($skills, array_map('trim', explode(',', $skills_other)));
        }
        $profile->skills = json_encode($skills);
        $profile->is_4ps = $request->has('is_4ps');
        $profile->save();

        // Handle job preferences
        if ($request->has('job_preferences')) {
            // Delete existing preferences
            $user->jobPreferences()->delete();
            
            // Save new preferences
            foreach ($request->job_preferences as $preference) {
                if (!empty($preference['preferred_job_title']) && !empty($preference['preferred_classification'])) {
                    JobPreference::create([
                        'user_id' => $user->id,
                        'preferred_job_title' => $preference['preferred_job_title'],
                        'preferred_classification' => $preference['preferred_classification'],
                        'min_salary' => $preference['min_salary'] ?? null,
                        'max_salary' => $preference['max_salary'] ?? null,
                        'preferred_location' => $preference['preferred_location'] ?? null,
                        'preferred_employment_type' => $preference['preferred_employment_type'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }

}