<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobs;


class JobController extends Controller
{
    public function index()
    {
        // Logic to display a list of jobs
        $jobs = Jobs::with(['user.employerProfile'])->get();
        return view('users.jobseekers.jobs.index', compact('jobs'));
    }

    public function show($id)
    {
        // Logic to display a single job
        $job = Jobs::with(['user.employerProfile'])->findOrFail($id);
        return view('users.jobseekers.jobs.show', compact('job'));
    }
}
