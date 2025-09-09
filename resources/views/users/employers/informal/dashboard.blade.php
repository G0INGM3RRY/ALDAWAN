@extends('layouts.dashboard')
@section('content')

    <h1 class="mb-4">
        <span class="badge bg-warning me-2">Informal</span>Employer Dashboard
    </h1>
    
    <!-- Business Overview Card -->
    @if($user->employerProfile)
    <div class="card mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">{{ $user->employerProfile->company_name ?? 'Business Profile' }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Business Name:</strong><br>
                    <small class="text-muted">{{ $user->employerProfile->company_name ?? 'Not specified' }}</small>
                </div>
                <div class="col-md-4">
                    <strong>Location:</strong><br>
                    <small class="text-muted">
                        @if($user->employerProfile->street || $user->employerProfile->barangay || $user->employerProfile->municipality || $user->employerProfile->province)
                            {{ collect([$user->employerProfile->street, $user->employerProfile->barangay, $user->employerProfile->municipality, $user->employerProfile->province])->filter()->implode(', ') }}
                        @else
                            Not specified
                        @endif
                    </small>
                </div>
                <div class="col-md-4">
                    <strong>Type:</strong><br>
                    <span class="badge bg-warning text-dark">{{ ucfirst($user->employerProfile->employer_type) }} Employer</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Navigation Cards -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('employers.jobs.index') }}" class="text-decoration-none">
                <div class="card h-100 border-warning">
                    <div class="card-body text-center">
                        <i class="fas fa-tools fa-2x text-warning mb-3"></i>
                        <h5 class="card-title text-warning">Posted Jobs</h5>
                        <p class="card-text text-muted">View and manage your job listings</p>
                        <small class="text-warning">Household services, contracts & short-term work</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('employers.jobs.create') }}" class="text-decoration-none">
                <div class="card h-100 border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-plus-circle fa-2x text-success mb-3"></i>
                        <h5 class="card-title text-success">Post a job</h5>
                        <p class="card-text text-muted">Create informal work opportunities</p>
                        <small class="text-success">Flexible, contract-based positions</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('employers.edit') }}" class="text-decoration-none">
                <div class="card h-100 border-info">
                    <div class="card-body text-center">
                        <i class="fas fa-user-tie fa-2x text-info mb-3"></i>
                        <h5 class="card-title text-info">Business Profile</h5>
                        <p class="card-text text-muted">Update your business information</p>
                        <small class="text-info">Build trust with potential workers</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Gigs Stats -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-warning">{{ $user->jobs->where('status', 'open')->count() }}</h4>
                            <small class="text-muted">Active Jobs</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-primary">{{ $user->jobs->where('job_type', 'formal')->count() }}</h4>
                            <small class="text-muted">Formal Work</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">{{ $user->jobs->where('job_type', 'informal')->count() }}</h4>
                            <small class="text-muted">Jobs</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">{{ $user->jobs->where('status', 'filled')->count() }}</h4>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    
@endsection
