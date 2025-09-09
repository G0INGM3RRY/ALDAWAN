<?php

use App\Http\Controllers\EmployerProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\JobseekerProfileController;
use App\Http\Controllers\JobController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Jobs;


/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Main dashboard redirect based on user role
    Route::get('/dashboard', function () {
        $user = Auth::user();
        
        if ($user->role === 'employer') {
            return redirect()->route('employers.dashboard');
        } else {
            return redirect()->route('jobseekers.dashboard');
        }
    })->name('dashboard');

    // Role-specific dashboards
    Route::get('/employers/dashboard', function () {
        $user = Auth::user();
        if ($user && $user->employerProfile && $user->employerProfile->employer_type === 'informal') {
            return view('users.employers.informal.dashboard', compact('user'));
        } else {
            return view('users.employers.formal.dashboard', compact('user'));
        }
    })->name('employers.dashboard');

    Route::get('/jobseekers/dashboard', function () {
        $user = Auth::user();
        $profile = $user->jobseekerProfile;
        
        if ($profile && $profile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.dashboard');
        } else {
            return view('users.jobseekers.formal.dashboard');
        }
    })->name('jobseekers.dashboard');
});

/*
|--------------------------------------------------------------------------
| User Profile Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Default Laravel profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Employer Routes
|--------------------------------------------------------------------------
*/

Route::prefix('employers')->middleware(['auth', 'employer.complete'])->group(function () {
    Route::get('/complete', [EmployerProfileController::class, 'complete'])->name('employers.complete')->withoutMiddleware('employer.complete');
    Route::get('/edit', [EmployerProfileController::class, 'edit'])->name('employers.edit');
    Route::put('/update', [EmployerProfileController::class, 'update'])->name('employers.update')->withoutMiddleware('employer.complete');
    Route::get('/create', [EmployerProfileController::class, 'create'])->name('employers.create');
    Route::get('/jobs', [EmployerProfileController::class, 'index'])->name('employers.jobs.index');
    Route::get('/jobs/create', [EmployerProfileController::class, 'createJob'])->name('employers.jobs.create');
    Route::post('/jobs', [EmployerProfileController::class, 'storeJob'])->name('employers.jobs.store');
    Route::get('/jobs/{job}/edit', [EmployerProfileController::class, 'editJob'])->name('employers.jobs.edit');
    Route::put('/jobs/{job}', [EmployerProfileController::class, 'updateJob'])->name('employers.jobs.update');
    Route::delete('/jobs/{job}', [EmployerProfileController::class, 'deleteJob'])->name('employers.jobs.delete');
    Route::get('/profile', [EmployerProfileController::class, 'show'])->name('employers.show');

    
});
//added offline
Route::get('/employers/jobs/{job}/applications',[EmployerProfileController::class,'viewapplications'])->name('employers.viewapplications');
Route::get('/employers/applications/{application}', [EmployerProfileController::class, 'viewApplication'])->name('employers.applications.view');
Route::patch('/employers/applications/{application}/accept', [EmployerProfileController::class, 'acceptApplication'])->name('employers.applications.accept');
Route::patch('/employers/applications/{application}/reject', [EmployerProfileController::class, 'rejectApplication'])->name('employers.applications.reject');
/*
|--------------------------------------------------------------------------
| Jobseeker Routes
|--------------------------------------------------------------------------
*/

Route::prefix('jobseekers')->middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('jobseekers.index');
    
    // Formal Jobseeker Routes
    Route::get('/edit', [JobseekerProfileController::class, 'edit'])->name('jobseekers.edit');
    Route::put('/update', [JobseekerProfileController::class, 'update'])->name('jobseekers.update');
    
    // Informal Jobseeker Routes
    Route::get('/informal/edit', [JobseekerProfileController::class, 'editInformal'])->name('jobseekers.informal.edit');
    Route::put('/informal/update', [JobseekerProfileController::class, 'updateInformal'])->name('jobseekers.informal.update');
    
    // Formal Job Application Routes
    Route::get('/applications', [JobApplicationController::class, 'myApplications'])->name('jobseekers.applications');
    Route::get('/applications/{application}', [JobApplicationController::class, 'show'])->name('jobseekers.applications.show');
    Route::put('/applications/{application}', [JobApplicationController::class, 'update'])->name('jobseekers.applications.update');
    Route::delete('/applications/{application}/withdraw', [JobApplicationController::class, 'withdraw'])->name('jobseekers.applications.withdraw');
        
});

Route::post('/jobseeker/complete', [JobseekerProfileController::class, 'store'])->middleware(['auth'])->name('jobseeker.complete');
Route::post('/jobseeker/informal/complete', [JobseekerProfileController::class, 'storeInformal'])->middleware(['auth'])->name('jobseeker.informal.complete');

/*
|--------------------------------------------------------------------------
| Additional User Management Routes
|--------------------------------------------------------------------------
*/

// Jobseeker profile resource
Route::resource('jobseeker-profiles', JobseekerProfileController::class);

// Limited user controller routes - only the ones we actually use
Route::controller(UserController::class)->group(function() {
    Route::get('/users/{user}', 'show')->name('users.show');
    Route::get('/users/{user}/complete', 'complete')->name('users.complete'); 
});


// Job Controller  
Route::get('/jobs/formal', [JobController::class, 'formalJobs'])->name('jobs.formal');
Route::get('/jobs/informal', [JobController::class, 'informalJobs'])->name('jobs.informal');
Route::resource('jobs', JobController::class);
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Job Application Routes (public access with auth middleware on actions)
Route::get('/jobs/{job}/apply', [JobApplicationController::class, 'quickApply'])->name('jobs.apply');
Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store'])->name('jobs.apply.store');
Route::post('/jobs/{job}/quick-apply', [JobApplicationController::class, 'quickStore'])->name('jobs.apply.quick');