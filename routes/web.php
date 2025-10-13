<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now you may create something great!
|
*/

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
});

/*
|--------------------------------------------------------------------------
| User Profile Routes (Laravel Breeze Default)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Role-Specific Route Files
|--------------------------------------------------------------------------
*/

// Load employer-specific routes
require __DIR__.'/employer.php';

// Load jobseeker-specific routes  
require __DIR__.'/jobseeker.php';

// Load job and application routes
require __DIR__.'/jobs.php';