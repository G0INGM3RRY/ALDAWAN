<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Jobs;
use App\Models\JobApplication;
use App\Models\CompanyVerification;
use App\Models\InformalEmployerVerification;
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

        // Chart data - Users by month (last 6 months)
        $chartData = [
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

        // Recent activity
        $recentUsers = User::latest()->take(5)->get();
        $recentJobs = Jobs::with('user')->latest()->take(5)->get();
        $recentApplications = JobApplication::with(['user', 'job'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'chartData', 'recentUsers', 'recentJobs', 'recentApplications'));
    }

    /**
     * User Management - List all users
     */
    public function users(Request $request)
    {
        // Get search and filter parameters
        $search = $request->get('search');
        $role = $request->get('role'); // Filter by role (admin/employer/seeker)
        $verified = $request->get('verified'); // Filter by email verification status
        $status = $request->get('status', 'active'); // Filter by status (active/archived/all)
        
        // Start building the query
        $query = User::with(['employer', 'jobseekerProfile']);
        
        // Apply status filter
        if ($status === 'archived') {
            $query->onlyTrashed(); // Show only soft-deleted users
        } elseif ($status === 'all') {
            $query->withTrashed(); // Show both active and archived
        }
        // If status is 'active' (default), no need to modify query - it already excludes trashed
        
        // Apply search filter (search in name and email)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        
        // Apply role filter
        if ($role) {
            $query->where('role', $role);
        }
        
        // Apply email verification filter
        if ($verified === 'yes') {
            $query->whereNotNull('email_verified_at');
        } elseif ($verified === 'no') {
            $query->whereNull('email_verified_at');
        }
        
        // Get paginated results
        $users = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show specific user details
     */
    public function showUser(User $user)
    {
        $user->load([
            'employer.verification',
            'employer.informalVerification',
            'jobseekerProfile.formalVerification',
            'jobseekerProfile.informalVerification'
        ]);
        
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
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:seeker,employer,admin',
            'verification_status' => 'required|in:verified,not_verified',
            'account_status' => 'required|in:active,inactive,suspended',
        ]);
        
        // Update basic fields
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->account_status = $request->account_status;
        
        // Handle verification status - approve DOCUMENT verifications, not just email
        if ($request->verification_status === 'verified') {
            // Set email verification
            if (!$user->email_verified_at) {
                $user->email_verified_at = now();
            }
            
            // Approve document verifications based on user type
            if ($user->role === 'employer' && $user->employer) {
                if ($user->employer->employer_type === 'formal') {
                    // Approve formal employer (company) verification
                    $verification = $user->employer->verification;
                    if ($verification && $verification->status !== 'approved') {
                        $verification->update([
                            'status' => 'approved',
                            'verified_by' => auth()->id(),
                            'verified_at' => now(),
                            'verification_notes' => 'Manually verified by admin'
                        ]);
                        $user->employer->update(['is_verified' => true]);
                    }
                } else {
                    // Approve informal employer verification
                    $verification = $user->employer->informalVerification;
                    if ($verification && $verification->status !== 'approved') {
                        $verification->update([
                            'status' => 'approved',
                            'verified_by' => auth()->id(),
                            'verified_at' => now(),
                            'verification_notes' => 'Manually verified by admin'
                        ]);
                    }
                }
            } elseif ($user->role === 'seeker' && $user->jobseekerProfile) {
                if ($user->jobseekerProfile->job_seeker_type === 'formal') {
                    // Approve formal jobseeker verification
                    $verification = $user->jobseekerProfile->formalVerification;
                    if ($verification && $verification->status !== 'approved') {
                        $verification->approve(auth()->user(), 'Manually verified by admin');
                    }
                } else {
                    // Approve informal jobseeker verification
                    $verification = $user->jobseekerProfile->informalVerification;
                    if ($verification && $verification->status !== 'approved') {
                        $verification->approve(auth()->user(), 'Manually verified by admin');
                    }
                }
            }
        } elseif ($request->verification_status === 'pending') {
            // Set document verifications back to pending (requires resubmission/review)
            if ($user->role === 'employer' && $user->employer) {
                if ($user->employer->employer_type === 'formal') {
                    $verification = $user->employer->verification;
                    if ($verification) {
                        $verification->update([
                            'status' => 'pending',
                            'verification_notes' => 'Marked for review by admin - please check documents'
                        ]);
                        $user->employer->update(['is_verified' => false]);
                    }
                } else {
                    $verification = $user->employer->informalVerification;
                    if ($verification) {
                        $verification->update([
                            'status' => 'pending',
                            'verification_notes' => 'Marked for review by admin - please check documents'
                        ]);
                    }
                }
            } elseif ($user->role === 'seeker' && $user->jobseekerProfile) {
                if ($user->jobseekerProfile->job_seeker_type === 'formal') {
                    $verification = $user->jobseekerProfile->formalVerification;
                    if ($verification) {
                        $verification->update([
                            'status' => 'pending',
                            'verification_notes' => 'Marked for review by admin - please check documents'
                        ]);
                    }
                } else {
                    $verification = $user->jobseekerProfile->informalVerification;
                    if ($verification) {
                        $verification->update([
                            'status' => 'pending',
                            'verification_notes' => 'Marked for review by admin - please check documents'
                        ]);
                    }
                }
            }
        } else {
            // Not Verified - Clear email verification and reject documents
            $user->email_verified_at = null;
            
            // Reject document verifications
            if ($user->role === 'employer' && $user->employer) {
                if ($user->employer->employer_type === 'formal') {
                    $verification = $user->employer->verification;
                    if ($verification) {
                        $verification->update([
                            'status' => 'rejected',
                            'verified_by' => auth()->id(),
                            'verified_at' => now(),
                            'rejection_reason' => 'Verification reset by admin'
                        ]);
                        $user->employer->update(['is_verified' => false]);
                    }
                } else {
                    $verification = $user->employer->informalVerification;
                    if ($verification) {
                        $verification->update([
                            'status' => 'rejected',
                            'verified_by' => auth()->id(),
                            'verified_at' => now(),
                            'rejection_reason' => 'Verification reset by admin'
                        ]);
                    }
                }
            } elseif ($user->role === 'seeker' && $user->jobseekerProfile) {
                if ($user->jobseekerProfile->job_seeker_type === 'formal') {
                    $verification = $user->jobseekerProfile->formalVerification;
                    if ($verification) {
                        $verification->reject(auth()->user(), 'Verification reset by admin');
                    }
                } else {
                    $verification = $user->jobseekerProfile->informalVerification;
                    if ($verification) {
                        $verification->reject(auth()->user(), 'Verification reset by admin');
                    }
                }
            }
        }
        
        $user->save();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully.');
    }

    /**
     * Delete user
     */
    public function deleteUser(Request $request, User $user)
    {
        // Check if this is a permanent delete or soft delete (archive)
        if ($request->input('force_delete') == '1') {
            // Permanent delete
            $userName = $user->name;
            $user->forceDelete();
            
            return redirect()->route('admin.users.index')
                ->with('success', "User '$userName' has been permanently deleted.");
        } else {
            // Soft delete (archive)
            $userName = $user->name;
            $user->delete();
            
            return redirect()->route('admin.users.index')
                ->with('success', "User '$userName' has been archived successfully.");
        }
    }

    /**
     * Restore a soft-deleted (archived) user
     */
    public function restoreUser($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        
        if (!$user->trashed()) {
            return redirect()->back()->with('info', 'User is already active.');
        }
        
        $user->restore();
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', "User '{$user->name}' has been restored successfully.");
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
    public function verifications(Request $request)
    {
        // Get search and filter parameters from the URL
        $search = $request->get('search'); // Search text
        $status = $request->get('status'); // Filter by status (pending/approved/rejected)
        
        // Start building queries for each verification type
        
        // 1. Company Verifications (Formal Employers)
        $companyQuery = CompanyVerification::with('employer.user');
        
        // If there's a search term, search in company name and user email
        if ($search) {
            $companyQuery->where(function($q) use ($search) {
                $q->whereHas('employer', function($query) use ($search) {
                    $query->where('company_name', 'like', '%' . $search . '%')
                          ->orWhereHas('user', function($userQuery) use ($search) {
                              $userQuery->where('email', 'like', '%' . $search . '%')
                                       ->orWhere('name', 'like', '%' . $search . '%');
                          });
                });
            });
        }
        
        // If there's a status filter, apply it
        if ($status) {
            $companyQuery->where('status', $status);
        }
        
        $companyVerifications = $companyQuery->orderBy('created_at', 'desc')->paginate(10);
        
        // 2. Informal Employer Verifications
        $informalEmployerQuery = InformalEmployerVerification::with('employer.user');
        
        if ($search) {
            $informalEmployerQuery->where(function($q) use ($search) {
                $q->whereHas('employer', function($query) use ($search) {
                    $query->where('company_name', 'like', '%' . $search . '%')
                          ->orWhereHas('user', function($userQuery) use ($search) {
                              $userQuery->where('email', 'like', '%' . $search . '%')
                                       ->orWhere('name', 'like', '%' . $search . '%');
                          });
                });
            });
        }
        
        if ($status) {
            $informalEmployerQuery->where('status', $status);
        }
        
        $informalEmployerVerifications = $informalEmployerQuery->orderBy('created_at', 'desc')->paginate(10);
        
        // 3. Formal Jobseeker Verifications
        $formalJobseekerQuery = FormalJobseekerVerification::with('jobseekerProfile.user');
        
        if ($search) {
            $formalJobseekerQuery->where(function($q) use ($search) {
                $q->whereHas('jobseekerProfile.user', function($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }
        
        if ($status) {
            $formalJobseekerQuery->where('status', $status);
        }
        
        $formalJobseekerVerifications = $formalJobseekerQuery->orderBy('created_at', 'desc')->paginate(10);
        
        // 4. Informal Jobseeker Verifications
        $informalJobseekerQuery = InformalJobseekerVerification::with('jobseekerProfile.user');
        
        if ($search) {
            $informalJobseekerQuery->where(function($q) use ($search) {
                $q->whereHas('jobseekerProfile.user', function($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%');
                });
            });
        }
        
        if ($status) {
            $informalJobseekerQuery->where('status', $status);
        }
        
        $informalJobseekerVerifications = $informalJobseekerQuery->orderBy('created_at', 'desc')->paginate(10);

        // Calculate Statistics (for all records, not just filtered)
        $stats = [
            'company_pending' => CompanyVerification::where('status', 'pending')->count(),
            'company_approved' => CompanyVerification::where('status', 'approved')->count(),
            'company_rejected' => CompanyVerification::where('status', 'rejected')->count(),
            'company_total' => CompanyVerification::count(),
            
            'informal_employer_pending' => InformalEmployerVerification::where('status', 'pending')->count(),
            'informal_employer_approved' => InformalEmployerVerification::where('status', 'approved')->count(),
            'informal_employer_rejected' => InformalEmployerVerification::where('status', 'rejected')->count(),
            'informal_employer_total' => InformalEmployerVerification::count(),
            
            'formal_jobseeker_pending' => FormalJobseekerVerification::where('status', 'pending')->count(),
            'formal_jobseeker_approved' => FormalJobseekerVerification::where('status', 'approved')->count(),
            'formal_jobseeker_rejected' => FormalJobseekerVerification::where('status', 'rejected')->count(),
            'formal_jobseeker_total' => FormalJobseekerVerification::count(),
            
            'informal_jobseeker_pending' => InformalJobseekerVerification::where('status', 'pending')->count(),
            'informal_jobseeker_approved' => InformalJobseekerVerification::where('status', 'approved')->count(),
            'informal_jobseeker_rejected' => InformalJobseekerVerification::where('status', 'rejected')->count(),
            'informal_jobseeker_total' => InformalJobseekerVerification::count(),
        ];

        $stats['total_pending'] = $stats['company_pending'] + $stats['informal_employer_pending'] + 
                                  $stats['formal_jobseeker_pending'] + $stats['informal_jobseeker_pending'];
        $stats['total_approved'] = $stats['company_approved'] + $stats['informal_employer_approved'] + 
                                   $stats['formal_jobseeker_approved'] + $stats['informal_jobseeker_approved'];
        $stats['total_rejected'] = $stats['company_rejected'] + $stats['informal_employer_rejected'] + 
                                   $stats['formal_jobseeker_rejected'] + $stats['informal_jobseeker_rejected'];
        $stats['total_all'] = $stats['company_total'] + $stats['informal_employer_total'] + 
                              $stats['formal_jobseeker_total'] + $stats['informal_jobseeker_total'];

        return view('admin.verifications.index', compact(
            'companyVerifications',
            'informalEmployerVerifications', 
            'formalJobseekerVerifications',
            'informalJobseekerVerifications',
            'stats'
        ));
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
     * Approve informal employer verification
     */
    public function approveInformalEmployerVerification(InformalEmployerVerification $verification)
    {
        $verification->update([
            'status' => 'approved',
            'verified_at' => now(),
            'verified_by' => auth()->id(),
            'verification_notes' => 'Approved by admin'
        ]);

        return redirect()->back()->with('success', 'Informal employer verification approved successfully');
    }

    /**
     * Reject informal employer verification
     */
    public function rejectInformalEmployerVerification(Request $request, InformalEmployerVerification $verification)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $verification->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'verified_by' => auth()->id(),
            'verified_at' => now()
        ]);

        return redirect()->back()->with('success', 'Informal employer verification rejected');
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
    public function jobs(Request $request)
    {
        // Get search and filter parameters
        $search = $request->get('search');
        $status = $request->get('status');
        $job_type = $request->get('job_type');
        $employment_type = $request->get('employment_type');
        
        // Start building the query
        $query = Jobs::with(['user.employer', 'applications']);
        
        // Apply search filter (search in job title, description, and company name)
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('job_title', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhereHas('user.employer', function($empQuery) use ($search) {
                      $empQuery->where('company_name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Apply status filter
        if ($status) {
            $query->where('status', $status);
        }
        
        // Apply job type filter
        if ($job_type) {
            $query->where('job_type', $job_type);
        }
        
        // Apply employment type filter
        if ($employment_type) {
            $query->where('employment_type', $employment_type);
        }
        
        // Get paginated results
        $jobs = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Calculate statistics
        $stats = [
            'total_jobs' => Jobs::count(),
            'open_jobs' => Jobs::where('status', 'open')->count(),
            'closed_jobs' => Jobs::where('status', 'closed')->count(),
            'filled_jobs' => Jobs::where('status', 'filled')->count(),
            'total_applications' => JobApplication::count(),
        ];

        return view('admin.jobs.index', compact('jobs', 'stats'));
    }

    /**
     * Update job status
     */
    public function updateJobStatus(Request $request, Jobs $job)
    {
        $request->validate([
            'status' => 'required|in:open,closed,filled'
        ]);

        $job->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Job status updated successfully');
    }

    /**
     * Delete a job
     */
    public function deleteJob(Jobs $job)
    {
        $jobTitle = $job->job_title;
        
        // Delete the job (applications will be handled by database cascade or manually)
        $job->delete();

        return redirect()->route('admin.jobs')->with('success', "Job '$jobTitle' has been deleted successfully");
    }

    /**
     * Show create job form for admin
     */
    public function createJob()
    {
        // Get all employers (both formal and informal)
        $employers = User::where('role', 'employer')
            ->with('employer')
            ->orderBy('name')
            ->get();
        
        // Get classifications and other necessary data
        $classifications = \App\Models\Classification::active()->orderBy('name')->get();
        $disabilities = \App\Models\Disability::active()->orderBy('name')->get();
        $skills = \App\Models\Skill::orderBy('name')->take(50)->get();
        $educationLevels = \App\Models\EducationLevel::active()->orderBy('name')->get();

        return view('admin.jobs.create', compact('employers', 'classifications', 'disabilities', 'skills', 'educationLevels'));
    }

    /**
     * Store job created by admin
     */
    public function storeJob(Request $request)
    {
        $validated = $request->validate([
            'employer_id' => 'required|exists:users,id',
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|in:full_time,part_time,contract,temporary,internship',
            'classification' => 'required|string|max:255',
            'additional_classifications' => 'nullable|array',
            'additional_classifications.*' => 'exists:classifications,id',
            'minimum_education_level_id' => 'nullable|exists:education_levels,id',
            'minimum_experience_years' => 'nullable|integer|min:0',
            'required_skills' => 'nullable|array',
            'required_skills.*' => 'exists:skills,id',
            'disabilities' => 'nullable|array',
            'disabilities.*' => 'exists:disabilities,id',
            'accessibility_notes' => 'nullable|string',
            'remote_work_available' => 'nullable|boolean',
            'positions_available' => 'nullable|integer|min:1',
            'benefits' => 'nullable|string',
            'status' => 'nullable|in:open,closed',
        ]);

        // Create job
        $job = Jobs::create([
            'company_id' => $validated['employer_id'],
            'job_title' => $validated['job_title'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'salary' => $validated['salary'],
            'location' => $validated['location'],
            'employment_type' => $validated['employment_type'],
            'classification' => $validated['classification'],
            'minimum_education_level_id' => $validated['minimum_education_level_id'] ?? null,
            'minimum_experience_years' => $validated['minimum_experience_years'] ?? 0,
            'accessibility_notes' => $validated['accessibility_notes'] ?? null,
            'remote_work_available' => $validated['remote_work_available'] ?? false,
            'positions_available' => $validated['positions_available'] ?? 1,
            'benefits' => $validated['benefits'] ?? null,
            'status' => $validated['status'] ?? 'open',
        ]);

        // Attach additional classifications
        if (!empty($validated['additional_classifications'])) {
            $job->classifications()->attach($validated['additional_classifications']);
        }

        // Attach required skills
        if (!empty($validated['required_skills'])) {
            $job->requiredSkills()->attach($validated['required_skills']);
        }

        // Attach disabilities (stored as disability_restrictions JSON)
        if (!empty($validated['disabilities'])) {
            $job->update(['disability_restrictions' => json_encode($validated['disabilities'])]);
        }

        return redirect()->route('admin.jobs')->with('success', 'Job created successfully for employer');
    }

    /**
     * Show edit job form for admin
     */
    public function editJob(Jobs $job)
    {
        // Get all employers
        $employers = User::where('role', 'employer')
            ->with('employer')
            ->orderBy('name')
            ->get();
        
        // Get classifications and other necessary data
        $classifications = \App\Models\Classification::active()->orderBy('name')->get();
        $disabilities = \App\Models\Disability::active()->orderBy('name')->get();
        $skills = \App\Models\Skill::orderBy('name')->take(50)->get();
        $educationLevels = \App\Models\EducationLevel::active()->orderBy('name')->get();

        // Load relationships
        $job->load(['classifications', 'requiredSkills', 'disabilities']);

        return view('admin.jobs.edit', compact('job', 'employers', 'classifications', 'disabilities', 'skills', 'educationLevels'));
    }

    /**
     * Update job edited by admin
     */
    public function updateJob(Request $request, Jobs $job)
    {
        $validated = $request->validate([
            'employer_id' => 'required|exists:users,id',
            'job_title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'required|string',
            'salary' => 'required|numeric|min:0',
            'location' => 'required|string|max:255',
            'employment_type' => 'required|in:full_time,part_time,contract,temporary,internship',
            'classification' => 'required|string|max:255',
            'additional_classifications' => 'nullable|array',
            'additional_classifications.*' => 'exists:classifications,id',
            'minimum_education_level_id' => 'nullable|exists:education_levels,id',
            'minimum_experience_years' => 'nullable|integer|min:0',
            'required_skills' => 'nullable|array',
            'required_skills.*' => 'exists:skills,id',
            'disabilities' => 'nullable|array',
            'disabilities.*' => 'exists:disabilities,id',
            'accessibility_notes' => 'nullable|string',
            'remote_work_available' => 'nullable|boolean',
            'positions_available' => 'nullable|integer|min:1',
            'benefits' => 'nullable|string',
            'status' => 'nullable|in:open,closed',
        ]);

        // Update job
        $job->update([
            'company_id' => $validated['employer_id'],
            'job_title' => $validated['job_title'],
            'description' => $validated['description'],
            'requirements' => $validated['requirements'],
            'salary' => $validated['salary'],
            'location' => $validated['location'],
            'employment_type' => $validated['employment_type'],
            'classification' => $validated['classification'],
            'minimum_education_level_id' => $validated['minimum_education_level_id'] ?? null,
            'minimum_experience_years' => $validated['minimum_experience_years'] ?? 0,
            'accessibility_notes' => $validated['accessibility_notes'] ?? null,
            'remote_work_available' => $validated['remote_work_available'] ?? false,
            'positions_available' => $validated['positions_available'] ?? 1,
            'benefits' => $validated['benefits'] ?? null,
            'status' => $validated['status'] ?? $job->status,
        ]);

        // Sync additional classifications
        if (isset($validated['additional_classifications'])) {
            $job->classifications()->sync($validated['additional_classifications']);
        } else {
            $job->classifications()->detach();
        }

        // Sync required skills
        if (isset($validated['required_skills'])) {
            $job->requiredSkills()->sync($validated['required_skills']);
        } else {
            $job->requiredSkills()->detach();
        }

        // Update disabilities (stored as disability_restrictions JSON)
        if (isset($validated['disabilities'])) {
            $job->update(['disability_restrictions' => json_encode($validated['disabilities'])]);
        } else {
            $job->update(['disability_restrictions' => null]);
        }

        return redirect()->route('admin.jobs')->with('success', 'Job updated successfully');
    }

    /**
     * Show job details for admin
     */
    public function showJob(Jobs $job)
    {
        // Load all relationships for admin view
        $job->load([
            'user.employer',
            'applications.jobseekerProfile.user',
            'requiredSkills',
            'classifications',
            'minimumEducationLevel'
        ]);

        return view('admin.jobs.show', compact('job'));
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
