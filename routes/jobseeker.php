<?php

use App\Http\Controllers\JobseekerProfileController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Jobseeker Routes
|--------------------------------------------------------------------------
|
| Here is where you can register jobseeker-specific routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::middleware(['auth'])->group(function () {
    // Jobseeker Dashboard
    Route::get('/jobseekers/dashboard', function () {
        $user = auth()->user();
        $profile = $user->jobseekerProfile;
        
        if ($profile && $profile->job_seeker_type === 'informal') {
            return view('users.jobseekers.informal.dashboard');
        } else {
            return view('users.jobseekers.formal.dashboard');
        }
    })->name('jobseekers.dashboard');

    // Jobseeker Profile Routes
    Route::prefix('jobseekers')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('jobseekers.index');
        
        // Formal Jobseeker Routes
        Route::get('/edit', [JobseekerProfileController::class, 'edit'])->name('jobseekers.edit');
        Route::put('/update', [JobseekerProfileController::class, 'update'])->name('jobseekers.update');
        
        // Informal Jobseeker Routes
        Route::get('/informal/edit', [JobseekerProfileController::class, 'editInformal'])->name('jobseekers.informal.edit');
        Route::put('/informal/update', [JobseekerProfileController::class, 'updateInformal'])->name('jobseekers.informal.update');
        
        // Job Application Routes
        Route::get('/applications', [JobApplicationController::class, 'myApplications'])->name('jobseekers.applications');
        Route::get('/applications/{application}', [JobApplicationController::class, 'show'])->name('jobseekers.applications.show');
        Route::put('/applications/{application}', [JobApplicationController::class, 'update'])->name('jobseekers.applications.update');
        Route::delete('/applications/{application}/withdraw', [JobApplicationController::class, 'withdraw'])->name('jobseekers.applications.withdraw');
    });

    // Profile Completion Routes
    Route::post('/jobseeker/complete', [JobseekerProfileController::class, 'store'])->name('jobseeker.complete');
    Route::post('/jobseeker/informal/complete', [JobseekerProfileController::class, 'storeInformal'])->name('jobseeker.informal.complete');

    // Jobseeker Resource Routes
    Route::resource('jobseeker-profiles', JobseekerProfileController::class);
});