<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here are the routes for admin functionality. These routes are loaded
| by the RouteServiceProvider within the admin middleware group.
|
*/

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Admin Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // User Management
    Route::get('/users', [AdminController::class, 'users'])->name('users.index');
    Route::get('/users/{user}', [AdminController::class, 'showUser'])->name('users.show');
    Route::get('/users/{user}/edit', [AdminController::class, 'editUser'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::patch('/users/{user}/status', [AdminController::class, 'updateUserStatus'])->name('users.updateStatus');
    Route::delete('/users/{user}', [AdminController::class, 'deleteUser'])->name('users.destroy'); 
    
    // Company Verification Management
    Route::get('/verifications', [AdminController::class, 'verifications'])->name('verifications');
    Route::patch('/verifications/{verification}/approve', [AdminController::class, 'approveVerification'])->name('verifications.approve');
    Route::patch('/verifications/{verification}/reject', [AdminController::class, 'rejectVerification'])->name('verifications.reject');
    
    // Formal Jobseeker Verification Management
    Route::prefix('verifications/formal')->name('verifications.formal.')->group(function () {
        Route::get('/', [AdminController::class, 'formalVerifications'])->name('index');
        Route::get('/{verification}', [AdminController::class, 'showFormalVerification'])->name('show');
        Route::patch('/{verification}/approve', [AdminController::class, 'approveFormalVerification'])->name('approve');
        Route::patch('/{verification}/reject', [AdminController::class, 'rejectFormalVerification'])->name('reject');
    });
    
    // Informal Jobseeker Verification Management
    Route::prefix('verifications/informal')->name('verifications.informal.')->group(function () {
        Route::get('/', [AdminController::class, 'informalVerifications'])->name('index');
        Route::get('/{verification}', [AdminController::class, 'showInformalVerification'])->name('show');
        Route::patch('/{verification}/approve', [AdminController::class, 'approveInformalVerification'])->name('approve');
        Route::patch('/{verification}/reject', [AdminController::class, 'rejectInformalVerification'])->name('reject');
    });
    
    // Job Management
    Route::get('/jobs', [AdminController::class, 'jobs'])->name('jobs');
    Route::patch('/jobs/{job}/status', [AdminController::class, 'updateJobStatus'])->name('jobs.updateStatus');
    
    // Reports
    Route::get('/reports', [AdminController::class, 'reports'])->name('reports');
});
