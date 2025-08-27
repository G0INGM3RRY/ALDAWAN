<?php

use App\Http\Controllers\EmployerProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
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
        return view('users.employers.dashboard');
    })->name('employers.dashboard');

    Route::get('/jobseekers/dashboard', function () {
        return view('users.jobseekers.dashboard');
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

Route::prefix('employers')->middleware('auth')->group(function () {
    Route::get('/complete', [EmployerProfileController::class, 'complete'])->name('employers.complete');
    Route::get('/edit', [EmployerProfileController::class, 'edit'])->name('employers.edit');
    Route::put('/update', [EmployerProfileController::class, 'update'])->name('employers.update');
    Route::get('/create', [EmployerProfileController::class, 'create'])->name('employers.create');
    Route::get('/jobs', [EmployerProfileController::class, 'index'])->name('employers.jobs.index');
    Route::get('/jobs/create', [EmployerProfileController::class, 'createJob'])->name('employers.jobs.create');
    Route::post('/jobs', [EmployerProfileController::class, 'storeJob'])->name('employers.jobs.store');
    Route::get('/jobs/{job}/edit', [EmployerProfileController::class, 'editJob'])->name('employers.jobs.edit');
    Route::put('/jobs/{job}', [EmployerProfileController::class, 'updateJob'])->name('employers.jobs.update');
    Route::delete('/jobs/{job}', [EmployerProfileController::class, 'deleteJob'])->name('employers.jobs.delete');
    Route::get('/profile', [EmployerProfileController::class, 'show'])->name('employers.show');
});

/*
|--------------------------------------------------------------------------
| Jobseeker Routes
|--------------------------------------------------------------------------
*/

Route::prefix('jobseekers')->middleware('auth')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('jobseekers.index');
    Route::get('/edit', [JobseekerProfileController::class, 'edit'])->name('jobseekers.edit');
    Route::put('/update', [JobseekerProfileController::class, 'update'])->name('jobseekers.update');
        
});

Route::post('/jobseeker/complete', [JobseekerProfileController::class, 'store'])->middleware(['auth'])->name('jobseeker.complete');

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
Route::resource('jobs', JobController::class);
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');