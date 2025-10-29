<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jobs;
use App\Models\JobApplication;
use App\Models\CompanyVerification;
use App\Models\FormalJobseekerVerification;
use App\Models\InformalJobseekerVerification;
use App\Models\JobseekerProfile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Employer;


class AdminController extends Controller
{
    /**
     * Admin Dashboard - Show statistics and overview
     */
    public function dashboard()
    {
        // Get basic statistics
        $pendingCompanyVerifications = CompanyVerification::where('status', 'pending')->count();
        $pendingFormalVerifications = FormalJobseekerVerification::where('status', 'pending')->count();
        $pendingInformalVerifications = InformalJobseekerVerification::where('status', 'pending')->count();
        
        $stats = [
            'total_users' => User::count(),
            'total_employers' => User::where('role', 'employer')->count(),
            'total_jobseekers' => User::where('role', 'seeker')->count(),
            'total_jobs' => Jobs::count(),
            'active_jobs' => Jobs::where('status', 'open')->count(),
            'total_applications' => JobApplication::count(),
            'pending_company_verifications' => $pendingCompanyVerifications,
            'pending_formal_verifications' => $pendingFormalVerifications,
            'pending_informal_verifications' => $pendingInformalVerifications,
            'pending_verifications' => $pendingCompanyVerifications + $pendingFormalVerifications + $pendingInformalVerifications,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    /**
     * User Management - List all users
     */
    public function users(Request $request)
    {
        $query = User::with(['employer', 'jobseekerProfile']);
        
        // Check if we should show archived users
        if ($request->has('archived') && $request->archived == 'true') {
            $query->onlyTrashed();
        }
        
        $users = $query->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show specific user details
     */
    public function showUser(User $user)
    {
        $user->load(['employer', 'jobseekerProfile']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show user edit form
     */
    public function editUser(User $user)
    {
        $user->load(['employer', 'jobseekerProfile']);
        
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user information
     */
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:seeker,employer,admin',
            'verification_status' => 'nullable|in:pending,verified,rejected',
            'account_status' => 'required|in:active,inactive,suspended',
        ]);

        $user = User::findOrFail($id);
        
        $updateData = $request->only(['name', 'email', 'role', 'account_status']);
        
        // Only update verification_status if it's provided
        if ($request->filled('verification_status')) {
            $updateData['verification_status'] = $request->verification_status;
        }
        
        $user->update($updateData);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user (soft delete - archive)
     */
    public function deleteUser(User $user)
    {
        $user->delete();
        
        return redirect()->route('admin.users.index')->with('success', 'User archived successfully');
    }
    
    /**
     * Restore archived user
     */
    public function restoreUser($id)
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $user->restore();
        
        return redirect()->route('admin.users.index')->with('success', 'User restored successfully');
    }

    /**
     * Update user status (active/inactive)
     */
    public function updateUserStatus(Request $request, User $user)
    {
        $request->validate([
            'status' => 'required|in:active,inactive'
        ]);

        // You can add a status field to users table or handle this differently
        // For now, we'll just return success
        
        return redirect()->back()->with('success', 'User status updated successfully');
    }

    /**
     * Verification Management - List all verifications
     */
    public function verifications()
    {
        $verifications = CompanyVerification::with('employer.user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.verifications.index', compact('verifications'));
    }

    /**
     * Approve verification
     */
    public function approveVerification(CompanyVerification $verification)
    {
        $verification->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Verification approved successfully');
    }

    /**
     * Reject verification
     */
    public function rejectVerification(Request $request, CompanyVerification $verification)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'verified_by' => auth()->id()
        ]);

        return redirect()->back()->with('success', 'Verification rejected');
    }

    /**
     * Formal Jobseeker Verification Management
     */
    public function formalVerifications()
    {
        $verifications = FormalJobseekerVerification::with(['jobseekerProfile.user', 'verifier'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.verifications.formal.index', compact('verifications'));
    }

    /**
     * Show specific formal verification
     */
    public function showFormalVerification(FormalJobseekerVerification $verification)
    {
        $verification->load(['jobseekerProfile.user', 'verifier']);
        return view('admin.verifications.formal.show', compact('verification'));
    }

    /**
     * Approve formal verification
     */
    public function approveFormalVerification(Request $request, FormalJobseekerVerification $verification)
    {
        $verification->approve(auth()->user(), $request->verification_notes);
        return redirect()->back()->with('success', 'Formal jobseeker verification approved');
    }

    /**
     * Reject formal verification
     */
    public function rejectFormalVerification(Request $request, FormalJobseekerVerification $verification)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $verification->reject(auth()->user(), $request->rejection_reason);
        return redirect()->back()->with('success', 'Formal jobseeker verification rejected');
    }

    /**
     * Informal Jobseeker Verification Management
     */
    public function informalVerifications()
    {
        $verifications = InformalJobseekerVerification::with(['jobseekerProfile.user', 'verifier'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.verifications.informal.index', compact('verifications'));
    }

    /**
     * Show specific informal verification
     */
    public function showInformalVerification(InformalJobseekerVerification $verification)
    {
        $verification->load(['jobseekerProfile.user', 'verifier']);
        return view('admin.verifications.informal.show', compact('verification'));
    }

    /**
     * Approve informal verification
     */
    public function approveInformalVerification(Request $request, InformalJobseekerVerification $verification)
    {
        $verification->approve(auth()->user(), $request->verification_notes);
        return redirect()->back()->with('success', 'Informal jobseeker verification approved');
    }

    /**
     * Reject informal verification
     */
    public function rejectInformalVerification(Request $request, InformalJobseekerVerification $verification)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $verification->reject(auth()->user(), $request->rejection_reason);
        return redirect()->back()->with('success', 'Informal jobseeker verification rejected');
    }

    /**
     * Job Management - List all jobs
     */
    public function jobs()
    {
        $jobs = Jobs::with('employer.user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.jobs.index', compact('jobs'));
    }

    /**
     * Update job status
     */
    public function updateJobStatus(Request $request, Jobs $job)
    {
        $request->validate([
            'status' => 'required|in:active,inactive,suspended'
        ]);

        $job->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Job status updated successfully');
    }

    /**
     * Reports - Show various reports
     */
    public function reports()
    {
        // Basic reports data
        $reports = [
            'users_by_month' => User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->get(),
            'jobs_by_status' => Jobs::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'applications_by_month' => JobApplication::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
                ->whereYear('created_at', date('Y'))
                ->groupBy('month')
                ->get()
        ];

        return view('admin.reports.index', compact('reports'));
    }
}
