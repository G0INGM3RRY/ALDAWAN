<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display the current jobseeker's profile view.
     * This method shows the logged-in jobseeker's profile information.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Eager load all necessary relationships for comprehensive profile display
        $user->load([
            'jobseekerProfile.skills',
            'jobseekerProfile.disabilities', 
            'jobseekerProfile.educationLevel',
            'jobseekerProfile.workExperiences',
            'jobPreferences'
        ]);
        
        $profile = $user->jobseekerProfile;
        
        // Route to appropriate index view based on job seeker type
        if ($profile && $profile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.index');
        } else {
            return view('users.jobseekers.formal.index');
        }
    }

    /**
     * Direct user to appropriate profile completion page after registration.
     * This is a legacy method that might be better handled by specialized controllers.
     */
    public function complete(User $user, Request $request)
    {
        if($user->role === 'seeker'){
            // Get the job_seeker_type from the request or session
            $job_seeker_type = $request->session()->get('job_seeker_type', $request->job_seeker_type);
            
            if ($job_seeker_type === 'informal') {
                // Get lookup data for informal complete form
                $informalSkills = \App\Models\Skill::getLimitedSkillsForDisplay('informal', 20);
                $disabilities = \App\Models\Disability::orderBy('name')->get();
                return view('users.jobseekers.informal.complete', compact('user', 'job_seeker_type', 'informalSkills', 'disabilities'));
            } else {
                // Get lookup data for formal complete form  
                $skills = \App\Models\Skill::getLimitedSkillsForDisplay('formal', 20);
                $disabilities = \App\Models\Disability::orderBy('name')->get();
                $educationLevels = \App\Models\EducationLevel::all();
                return view('users.jobseekers.formal.complete', compact('user', 'job_seeker_type', 'skills', 'disabilities', 'educationLevels'));
            }
        }elseif($user->role === 'employer'){
            return view('users.employers.complete', compact('user'));
        }
    }

    /**
     * Display specific jobseeker profile.
     */
    public function show(User $user)
    {
        // Only show jobseeker profiles
        if ($user->role !== 'seeker') {
            abort(404);
        }
        
        $profile = $user->jobseekerProfile;
        
        // Route to appropriate view based on job seeker type
        if ($profile && $profile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.index', compact('user'));
        } else {
            return view('users.jobseekers.formal.index', compact('user'));
        }
    }
}
