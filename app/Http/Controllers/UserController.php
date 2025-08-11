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
     * Display a listing of jobseekers.
     * This method is specifically used for the jobseekers index page.
     */
    public function index()
    {
        // Get only users with role 'seeker'
        $users = User::where('role', 'seeker')->get();
        return view('users.jobseekers.index', compact('users'));
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
            return view('users.jobseekers.complete', compact('user', 'job_seeker_type'));
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
        
        return view('users.jobseekers.show', compact('user'));
    }
}
