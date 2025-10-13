<?php

use App\Http\Controllers\EmployerProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Employer Routes
|--------------------------------------------------------------------------
|
| Here is where you can register employer-specific routes for your application.
| These routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group.
|
*/

Route::middleware(['auth'])->group(function () {
    // Employer Dashboard
    Route::get('/employers/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->employerProfile && $user->employerProfile->employer_type === 'informal') {
            return view('users.employers.informal.dashboard', compact('user'));
        } else {
            return view('users.employers.formal.dashboard', compact('user'));
        }
    })->name('employers.dashboard');

    // Employer Profile Routes
    Route::prefix('employers')->middleware(['employer.complete'])->group(function () {
        Route::get('/complete', [EmployerProfileController::class, 'complete'])->name('employers.complete')->withoutMiddleware('employer.complete');
        Route::get('/edit', [EmployerProfileController::class, 'edit'])->name('employers.edit');
        Route::put('/update', [EmployerProfileController::class, 'update'])->name('employers.update')->withoutMiddleware('employer.complete');
        Route::get('/create', [EmployerProfileController::class, 'create'])->name('employers.create');
        Route::get('/profile', [EmployerProfileController::class, 'show'])->name('employers.show');

        // Job Management Routes
        Route::get('/jobs', [EmployerProfileController::class, 'index'])->name('employers.jobs.index');
        Route::get('/jobs/create', [EmployerProfileController::class, 'createJob'])->name('employers.jobs.create');
        Route::post('/jobs', [EmployerProfileController::class, 'storeJob'])->name('employers.jobs.store');
        Route::get('/jobs/{job}/edit', [EmployerProfileController::class, 'editJob'])->name('employers.jobs.edit');
        Route::put('/jobs/{job}', [EmployerProfileController::class, 'updateJob'])->name('employers.jobs.update');
        Route::delete('/jobs/{job}', [EmployerProfileController::class, 'deleteJob'])->name('employers.jobs.delete');

        // Application Management Routes
        Route::get('/jobs/{job}/applications', [EmployerProfileController::class, 'viewapplications'])->name('employers.viewapplications');
        Route::get('/applications/{application}', [EmployerProfileController::class, 'viewApplication'])->name('employers.applications.view');
        Route::patch('/applications/{application}/accept', [EmployerProfileController::class, 'acceptApplication'])->name('employers.applications.accept');
        Route::patch('/applications/{application}/reject', [EmployerProfileController::class, 'rejectApplication'])->name('employers.applications.reject');
    });
});