<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use App\Models\JobseekerProfile;
use App\Models\User;
use App\Models\JobPreference;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Jobs;

class JobseekerProfileController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Check if profile already exists to prevent type changes
        $existingProfile = JobseekerProfile::where('user_id', $user->id)->first();
        if ($existingProfile && $existingProfile->job_seeker_type) {
            return redirect()->route('dashboard')->withErrors([
                'profile' => 'Profile already exists. Use edit instead.'
            ]);
        }

        $data = $request->validate([
            'job_seeker_type' => 'required|string|in:formal,informal',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'birthday' => 'nullable|date',
            'sex' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

         //photo logic
        if ($request->hasFile('photo')) {
         $file = $request->file('photo');
         $filename = time() . '_' . $file->getClientOriginalName();
         $filePath = $file->storeAs('photos', $filename, 'public');
         $data['photo'] = $filePath;
        }


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
        if ($request->has('job_preferences'))
         {
            // Delete existing preferences
            $user->jobPreferences()->delete();
            
            // Save new preferences
            foreach ($request->job_preferences as $preference) {
                if (!empty($preference['preferred_job_title']) && !empty($preference['preferred_classification'])) 
                {
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
        
        // Check if user is informal type and redirect to appropriate form
        if ($profile && $profile->job_seeker_type === 'informal') {
            return redirect()->route('jobseekers.informal.edit');
        }
        
        // Security check: Only allow formal users to access this form
        if ($profile && $profile->job_seeker_type !== 'formal') {
            return redirect()->route('dashboard')->with('error', 'Please complete your profile first.');
        }
        
        return view('users.jobseekers.formal.edit', compact('user', 'profile', 'jobPreferences'));
    }


    public function update(Request $request)
    {
        /** @var User $user */
        $user = Auth::user(); // Get the authenticated user

        // Get existing profile to check current job_seeker_type
        $existingProfile = JobseekerProfile::where('user_id', $user->id)->first();
        
        // Prevent job seeker type changes after initial registration
        if ($existingProfile && $existingProfile->job_seeker_type) {
            $requiredType = $existingProfile->job_seeker_type;
            
            // If user is trying to change type, reject with error
            if ($request->job_seeker_type !== $requiredType) {
                return redirect()->back()->withErrors([
                    'job_seeker_type' => 'Job seeker type cannot be changed after registration.'
                ])->withInput();
            }
        } else {
            $requiredType = 'formal'; // Default for existing update method
        }

        $data = $request->validate([
            'job_seeker_type' => 'required|string|in:' . $requiredType,
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'birthday' => 'nullable|date',
            'sex' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

           //photo logic
        if ($request->hasFile('photo')) {
         $file = $request->file('photo');
         $filename = time() . '_' . $file->getClientOriginalName();
         $filePath = $file->storeAs('photos', $filename, 'public');
         $data['photo'] = $filePath;
        }


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

    // Informal Jobseeker Methods
    public function editInformal()
    {
        $user = auth()->user();
        $profile = $user->jobseekerProfile; // Get the user's profile data
        
        // Security check: Only allow informal users to access this form
        if ($profile && $profile->job_seeker_type !== 'informal') {
            return redirect()->route('jobseekers.edit')->with('error', 'Access denied. You are not an informal worker.');
        }
        
        return view('users.jobseekers.informal.edit', compact('user', 'profile'));
    }

    public function storeInformal(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Check if profile already exists to prevent type changes
        $existingProfile = JobseekerProfile::where('user_id', $user->id)->first();
        if ($existingProfile && $existingProfile->job_seeker_type) {
            return redirect()->route('dashboard')->withErrors([
                'profile' => 'Profile already exists. Use edit instead.'
            ]);
        }

        // Force job_seeker_type to informal for security
        $request->merge(['job_seeker_type' => 'informal']);

        $data = $request->validate([
            'job_seeker_type' => 'required|string|in:informal',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'birthday' => 'nullable|date',
            'sex' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'civilstatus' => 'nullable|string',
            'street' => 'nullable|string',
            'barangay' => 'nullable|string',
            'municipality' => 'nullable|string',
            'province' => 'nullable|string',
            'contactnumber' => 'nullable|string',
            'email' => 'nullable|string',
            'disability' => 'nullable|array',
            'is_4ps' => 'nullable',
            'employmentstatus' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills_other' => 'nullable|string',
            'work_experience' => 'nullable|string',
            'preferred_work_type' => 'nullable|string',
            'preferred_schedule' => 'nullable|string',
            'preferred_salary_range' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('photos', $filename, 'public');
            $data['photo'] = $filePath;
        }

        $profile = JobseekerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = new JobseekerProfile();
            $profile->user_id = $user->id;
        }
        
        $profile->fill($data);
        $profile->disability = json_encode($request->input('disability', []));
        $profile->work_experience = $request->input('work_experience', '');
        
        $skills = $request->input('skills', []);
        $skills_other = $request->input('skills_other', '');
        if ($skills_other) {
            $skills = array_merge($skills, array_map('trim', explode(',', $skills_other)));
        }
        $profile->skills = json_encode($skills);
        $profile->is_4ps = $request->has('is_4ps');
        $profile->save();

        return redirect()->route('dashboard')->with('success', 'Profile completed successfully!');
    }

    public function updateInformal(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user

        // Get existing profile to ensure user is informal type
        $existingProfile = JobseekerProfile::where('user_id', $user->id)->first();
        
        // Security check: Only allow informal users to use this method
        if ($existingProfile && $existingProfile->job_seeker_type !== 'informal') {
            return redirect()->route('jobseekers.edit')->withErrors([
                'access' => 'Access denied. You are not an informal worker.'
            ]);
        }
        
        // Force job_seeker_type to informal to prevent tampering
        $request->merge(['job_seeker_type' => 'informal']);

        $data = $request->validate([
            'job_seeker_type' => 'required|string|in:informal',
            'first_name' => 'required|string',
            'middle_name' => 'nullable|string',
            'last_name' => 'required|string',
            'suffix' => 'nullable|string',
            'birthday' => 'nullable|date',
            'sex' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'civilstatus' => 'nullable|string',
            'street' => 'nullable|string',
            'barangay' => 'nullable|string',
            'municipality' => 'nullable|string',
            'province' => 'nullable|string',
            'contactnumber' => 'nullable|string',
            'email' => 'nullable|string',
            'disability' => 'nullable|array',
            'is_4ps' => 'nullable',
            'employmentstatus' => 'nullable|string',
            'skills' => 'nullable|array',
            'skills_other' => 'nullable|string',
            'work_experience' => 'nullable|string',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('photos', $filename, 'public');
            $data['photo'] = $filePath;
        }

        $profile = JobseekerProfile::where('user_id', $user->id)->first();
        if (!$profile) {
            $profile = new JobseekerProfile();
            $profile->user_id = $user->id;
        }
        
        $profile->fill($data);
        $profile->disability = json_encode($request->input('disability', []));
        $profile->work_experience = $request->input('work_experience', '');
        
        $skills = $request->input('skills', []);
        $skills_other = $request->input('skills_other', '');
        if ($skills_other) {
            $skills = array_merge($skills, array_map('trim', explode(',', $skills_other)));
        }
        $profile->skills = json_encode($skills);
        $profile->is_4ps = $request->has('is_4ps');
        $profile->save();

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
    }

}