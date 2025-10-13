@extends('layouts.dashboard')
@section('content')

    <h1 class="mb-4">
        <span class="badge bg-primary me-2">Formal</span>Employer Dashboard
    </h1>
    
    <!-- Company Overview Card -->
    @if($user->employerProfile)
    <div class="card mb-4 border-primary">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $user->employerProfile->company_name ?? 'Company Profile' }}</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <strong>Company Name:</strong><br>
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
                    <span class="badge bg-primary">{{ ucfirst($user->employerProfile->employer_type) }} Employer</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <!-- Navigation Cards -->
        <div class="col-md-4 mb-4">
            <a href="{{ route('employers.jobs.index') }}" class="text-decoration-none">
                <div class="card h-100 border-primary">
                    <div class="card-body text-center">
                        <i class="fas fa-briefcase fa-2x text-primary mb-3"></i>
                        <h5 class="card-title text-primary">Posted Jobs</h5>
                        <p class="card-text text-muted">View and manage your formal job listings</p>
                        <small class="text-primary">Professional positions with full benefits</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <a href="{{ route('employers.jobs.create') }}" class="text-decoration-none">
                <div class="card h-100 border-success">
                    <div class="card-body text-center">
                        <i class="fas fa-plus-circle fa-2x text-success mb-3"></i>
                        <h5 class="card-title text-success">Post a Job</h5>
                        <p class="card-text text-muted">Create professional job listings</p>
                        <small class="text-success">Full-time, part-time, or contract positions</small>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 border-info">
                <div class="card-body text-center">
                    <i class="fas fa-building fa-2x text-info mb-3"></i>
                    <h5 class="card-title text-info">Company Profile</h5>
                    <p class="card-text text-muted">View and manage your company information</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('employers.show') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-eye me-1"></i>View Profile
                        </a>
                        <a href="{{ route('employers.edit') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-edit me-1"></i>Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Jobs Stats -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <h4 class="text-primary">{{ $user->jobs->where('status', 'open')->count() }}</h4>
                            <small class="text-muted">Active Jobs</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-success">{{ $user->jobs->where('job_type', 'formal')->count() }}</h4>
                            <small class="text-muted">Formal Positions</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-warning">{{ $user->jobs->where('job_type', 'informal')->count() }}</h4>
                            <small class="text-muted">Contract Work</small>
                        </div>
                        <div class="col-md-3">
                            <h4 class="text-info">{{ $user->jobs->where('status', 'filled')->count() }}</h4>
                            <small class="text-muted">Filled Positions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
                    
@endsection
