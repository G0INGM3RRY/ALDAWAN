@extends('layouts.dashboard')
@section('content')

    <h1 class="mb-4">Employer Dashboard</h1>
    <div class="row">
        <!-- Navigation Cards -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('employers.jobs.index') }}" class="text-decoration-none">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <h5 class="card-title text-primary">Posted Jobs</h5>
                        <p class="card-text text-muted">View and manage your job listings</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('employers.jobs.create') }}" class="text-decoration-none">
                <div class="card h-100 border-success">
                    <div class="card-body text-center">
                        <h5 class="card-title text-success">Post a Job</h5>
                        <p class="card-text text-muted">Create a new job listing to find suitable candidates</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('employers.edit') }}" class="text-decoration-none">
                <div class="card h-100 border-info">
                    <div class="card-body text-center">
                        <h5 class="card-title text-info">Company Profile</h5>
                        <p class="card-text text-muted">Update your company information</p>
                    </div>
                </div>
            </a>
        </div>
    </div>
                    
@endsection                 