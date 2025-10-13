<?php
namespace App\Http\Controllers;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use App\Models\JobseekerProfile;
use App\Models\User;
use App\Models\JobPreference;
use App\Models\Skill;
use App\Models\Disability;
use App\Models\EducationLevel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            // email should come from user table, not stored in profile
            'disabilities' => 'nullable|array',
            'is_4ps' => 'nullable',
            'employmentstatus' => 'nullable|string',
            'education' => 'nullable|array',
            'work' => 'nullable|array',
            'skills' => 'nullable|array',
            'formal_skills' => 'nullable|array',
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
        
        // Remove the old JSON fields and email from the data array before filling
        $profileData = $data;
        unset($profileData['disabilities'], $profileData['education'], $profileData['skills'], $profileData['formal_skills'], $profileData['skills_other'], $profileData['email']);
        
        $profile->fill($profileData);
        $profile->is_4ps = $request->has('is_4ps');
        $profile->save();

        // Handle disabilities using the new relational structure
        if ($request->has('disabilities')) {
            $profile->disabilities()->sync($request->input('disabilities', []));
        }

        // Handle skills using the new relational structure
        $skillIds = [];
        if ($request->has('skills')) {
            $skillIds = array_merge($skillIds, $request->input('skills', []));
        }
        if ($request->has('formal_skills')) {
            $skillIds = array_merge($skillIds, $request->input('formal_skills', []));
        }
        if ($request->has('informal_skills')) {
            $skillIds = array_merge($skillIds, $request->input('informal_skills', []));
        }
        
        // Handle other skills (create new skills if needed)
        $skills_other = $request->input('skills_other', '');
        if ($skills_other) {
            $otherSkills = array_map('trim', explode(',', $skills_other));
            foreach ($otherSkills as $skillName) {
                if (!empty($skillName)) {
                    $skill = Skill::createOrGetCustomSkill($skillName, 'formal');
                    $skillIds[] = $skill->id;
                }
            }
        }
        
        if (!empty($skillIds)) {
            // Increment usage count for selected skills
            foreach ($skillIds as $skillId) {
                $skill = Skill::find($skillId);
                if ($skill) {
                    $skill->incrementUsage();
                }
            }
            $profile->skills()->sync(array_unique($skillIds));
        }



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
        $profile = JobseekerProfile::with(['skills', 'disabilities', 'educationLevel'])->where('user_id', $user->id)->first(); // Eager load relationships
        $jobPreferences = $user->jobPreferences; // Get user's job preferences
        
        // Check if user is informal type and redirect to appropriate form
        if ($profile && $profile->job_seeker_type === 'informal') {
            return redirect()->route('jobseekers.informal.edit');
        }
        
        // Security check: Only allow formal users to access this form
        if ($profile && $profile->job_seeker_type !== 'formal') {
            return redirect()->route('dashboard')->with('error', 'Please complete your profile first.');
        }
        
        // Load required data for the form
        $skills = Skill::getLimitedSkillsForDisplay('formal', 20);
        $disabilities = Disability::active()->orderBy('name')->get();
        $educationLevels = EducationLevel::active()->orderBy('name')->get();
        
        return view('users.jobseekers.formal.edit', compact('user', 'profile', 'jobPreferences', 'skills', 'disabilities', 'educationLevels'));
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
            // email should come from user table, not stored in profile
            'disabilities' => 'nullable|array',
            'disabilities.*' => 'exists:disabilities,id',
            'is_4ps' => 'nullable',
            'employmentstatus' => 'nullable|string',
            'education' => 'nullable|array',
            'work' => 'nullable|array',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
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

        DB::beginTransaction();
        
        try {
            // Remove relationship fields and email from data array for profile fill, but keep education fields
            $relationshipFields = ['disabilities', 'skills', 'work', 'email'];
            $profileData = collect($data)->except($relationshipFields)->toArray();
            
            // Handle education fields explicitly
            if ($request->filled('education_level_id')) {
                $profileData['education_level_id'] = $request->input('education_level_id');
            }
            
            // Handle other education fields
            $educationFields = ['institution_name', 'graduation_year', 'gpa', 'degree_field'];
            foreach ($educationFields as $field) {
                if ($request->filled($field)) {
                    $profileData[$field] = $request->input($field);
                }
            }
            
            $profile->fill($profileData);
            $profile->is_4ps = $request->has('is_4ps');
            $profile->save();

            \Log::info('Profile basic data saved for user: ' . $user->id);

            // Handle skills relationship
            $skillIds = $request->input('skills', []);
            \Log::info('Skills from request: ', $skillIds);
            
            // Handle additional skills from 'skills_other'
            if ($request->filled('skills_other')) {
                $otherSkills = array_map('trim', explode(',', $request->input('skills_other')));
                foreach ($otherSkills as $skillName) {
                    if (!empty($skillName)) {
                        $skill = Skill::firstOrCreate(['name' => $skillName]);
                        $skillIds[] = $skill->id;
                    }
                }
            }

            // Handle skills relationship using direct DB queries
            // First, remove existing skills
            DB::table('jobseeker_skills')->where('jobseeker_profile_id', $profile->id)->delete();
            
            // Then add new skills
            if (!empty($skillIds)) {
                $skillData = [];
                foreach ($skillIds as $skillId) {
                    $skillData[] = [
                        'jobseeker_profile_id' => $profile->id,
                        'skill_id' => $skillId,
                        'proficiency_level' => null,
                        'years_experience' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                DB::table('jobseeker_skills')->insert($skillData);
                \Log::info('Skills inserted via DB: ' . count($skillData) . ' skills');
            } else {
                \Log::info('No skills to insert');
            }

            // Handle disabilities relationship
            $disabilityIds = $request->input('disabilities', []);
            \Log::info('Disabilities from request: ', $disabilityIds);
            // Handle disabilities relationship using direct DB queries
            // First, remove existing disabilities
            DB::table('jobseeker_disabilities')->where('jobseeker_profile_id', $profile->id)->delete();
            
            // Then add new disabilities
            if (!empty($disabilityIds)) {
                $disabilityData = [];
                foreach ($disabilityIds as $disabilityId) {
                    $disabilityData[] = [
                        'jobseeker_profile_id' => $profile->id,
                        'disability_id' => $disabilityId,
                        'accommodation_needs' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                DB::table('jobseeker_disabilities')->insert($disabilityData);
                \Log::info('Disabilities inserted via DB: ' . count($disabilityData) . ' disabilities');
            } else {
                \Log::info('No disabilities to insert');
            }

            // Handle job preferences (existing logic)
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

            DB::commit();
            
            return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Profile update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    // Informal Jobseeker Methods
    public function editInformal()
    {
        $user = auth()->user();
        $profile = JobseekerProfile::with(['skills', 'disabilities'])->where('user_id', $user->id)->first(); // Eager load relationships
        
        // Security check: Only allow informal users to access this form
        if ($profile && $profile->job_seeker_type !== 'informal') {
            return redirect()->route('jobseekers.edit')->with('error', 'Access denied. You are not an informal worker.');
        }
        
        // Load required data for the form
        $disabilities = Disability::active()->orderBy('name')->get();
        $informalSkills = Skill::getLimitedSkillsForDisplay('informal', 20);
        
        return view('users.jobseekers.informal.edit', compact('user', 'profile', 'disabilities', 'informalSkills'));
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
            // email should come from user table, not stored in profile
            'is_4ps' => 'nullable',
            'employmentstatus' => 'nullable|string',
            'skills' => 'nullable|array',
            'informal_skills' => 'nullable|array',
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
        
        // Remove the old JSON fields from the data array before filling
        $profileData = $data;
        unset($profileData['skills'], $profileData['informal_skills'], $profileData['skills_other'], $profileData['work_experience']);
        
        $profile->fill($profileData);
        $profile->is_4ps = $request->has('is_4ps');
        $profile->save();

        // Handle skills using the new relational structure
        $skillIds = [];
        if ($request->has('skills')) {
            $skillIds = array_merge($skillIds, $request->input('skills', []));
        }
        
        // Handle other skills (create new skills if needed)
        $skills_other = $request->input('skills_other', '');
        if ($skills_other) {
            $otherSkills = array_map('trim', explode(',', $skills_other));
            foreach ($otherSkills as $skillName) {
                if (!empty($skillName)) {
                    $skill = Skill::createOrGetCustomSkill($skillName, 'informal');
                    $skillIds[] = $skill->id;
                }
            }
        }
        
        if (!empty($skillIds)) {
            // Increment usage count for selected skills
            foreach ($skillIds as $skillId) {
                $skill = Skill::find($skillId);
                if ($skill) {
                    $skill->incrementUsage();
                }
            }
            $profile->skills()->sync(array_unique($skillIds));
        }

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
            // email should come from user table, not stored in profile
            'disabilities' => 'nullable|array',
            'disabilities.*' => 'exists:disabilities,id',
            'is_4ps' => 'nullable',
            'employmentstatus' => 'nullable|string',
            'informal_skills' => 'nullable|array',
            'informal_skills.*' => 'exists:skills,id',
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

        DB::beginTransaction();
        
        try {
            // Remove relationship fields and email from data array
            $relationshipFields = ['disabilities', 'informal_skills', 'email'];
            $profileData = collect($data)->except($relationshipFields)->toArray();
            
            $profile->fill($profileData);
            $profile->is_4ps = $request->has('is_4ps');
            $profile->save();

            \Log::info('Informal profile basic data saved for user: ' . $user->id);

            // Handle disabilities relationship using direct DB queries
            DB::table('jobseeker_disabilities')->where('jobseeker_profile_id', $profile->id)->delete();
            
            $disabilityIds = $request->input('disabilities', []);
            if (!empty($disabilityIds)) {
                $disabilityData = [];
                foreach ($disabilityIds as $disabilityId) {
                    $disabilityData[] = [
                        'jobseeker_profile_id' => $profile->id,
                        'disability_id' => $disabilityId,
                        'accommodation_needs' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                DB::table('jobseeker_disabilities')->insert($disabilityData);
                \Log::info('Informal disabilities inserted via DB: ' . count($disabilityData) . ' disabilities');
            } else {
                \Log::info('No informal disabilities to insert');
            }

            // Handle skills relationship
            $skillIds = $request->input('informal_skills', []);
            \Log::info('Informal skills from request: ', $skillIds);
            
            // Handle additional skills from 'skills_other'
            if ($request->filled('skills_other')) {
                $otherSkills = array_map('trim', explode(',', $request->input('skills_other')));
                foreach ($otherSkills as $skillName) {
                    if (!empty($skillName)) {
                        $skill = Skill::firstOrCreate(['name' => $skillName]);
                        $skillIds[] = $skill->id;
                    }
                }
            }
            
            // Handle skills relationship using direct DB queries
            DB::table('jobseeker_skills')->where('jobseeker_profile_id', $profile->id)->delete();
            
            if (!empty($skillIds)) {
                $skillData = [];
                foreach ($skillIds as $skillId) {
                    $skillData[] = [
                        'jobseeker_profile_id' => $profile->id,
                        'skill_id' => $skillId,
                        'proficiency_level' => null,
                        'years_experience' => null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                DB::table('jobseeker_skills')->insert($skillData);
                \Log::info('Informal skills inserted via DB: ' . count($skillData) . ' skills');
            } else {
                \Log::info('No informal skills to insert');
            }

            DB::commit();
            
            return redirect()->route('dashboard')->with('success', 'Profile updated successfully!');
            
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Informal profile update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
        }
    }

}