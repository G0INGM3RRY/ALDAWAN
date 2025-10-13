<?php

use App\Http\Controllers\JobController;
use App\Http\Controllers\JobApplicationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Job & Application Routes
|--------------------------------------------------------------------------
|
| Public job browsing and application routes that can be accessed by
| both employers and jobseekers, or require specific permissions.
|
*/

// Public Job Browsing Routes
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/formal', [JobController::class, 'formalJobs'])->name('jobs.formal');
Route::get('/jobs/informal', [JobController::class, 'informalJobs'])->name('jobs.informal');
Route::get('/jobs/{job}', [JobController::class, 'show'])->name('jobs.show');

// Job Application Routes (require authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/jobs/{job}/apply', [JobApplicationController::class, 'quickApply'])->name('jobs.apply');
    Route::post('/jobs/{job}/apply', [JobApplicationController::class, 'store'])->name('jobs.apply.store');
    Route::post('/jobs/{job}/quick-apply', [JobApplicationController::class, 'quickStore'])->name('jobs.apply.quick');
});

// User Management Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/complete', [UserController::class, 'complete'])->name('users.complete'); 
});

// Job Resource Routes
Route::resource('jobs', JobController::class)->except(['index', 'show']);