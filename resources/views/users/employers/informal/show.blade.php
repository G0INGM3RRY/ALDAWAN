@extends('layouts.dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-home me-2"></i>My Employer Profile</h1>
        <a href="{{ route('employers.edit') }}" class="btn btn-primary">
            <i class="fas fa-edit me-1"></i>Edit Profile
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Employer Overview Card -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Employer Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    @if($profile && $profile->company_logo)
                        <img src="{{ asset('storage/' . $profile->company_logo) }}" 
                             alt="Profile Photo" class="img-fluid rounded mb-3" style="max-width: 150px;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3 mx-auto" 
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>{{ $profile->company_name ?? 'Name Not Set' }}</h4>
                            
                            <p class="text-muted mb-2">
                                <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                            </p>
                            
                            @if($profile && $profile->contactnumber)
                                <p class="text-muted mb-2">
                                    <i class="fas fa-phone me-1"></i>{{ $profile->contactnumber }}
                                </p>
                            @endif
                            
                            <div class="mb-3">
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-home me-1"></i>
                                    {{ $profile->employer_type ? ucfirst($profile->employer_type) : 'Not Set' }} Employer
                                </span>
                                
                                @if($profile && $profile->is_verified)
                                    <span class="badge bg-primary fs-6 ms-2">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Verified
                                    </span>
                                @else
                                    <span class="badge bg-secondary fs-6 ms-2">
                                        <i class="fas fa-clock me-1"></i>
                                        Unverified
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($profile)
                                <h6><i class="fas fa-map-marker-alt me-1"></i>Address</h6>
                                <p class="text-muted">
                                    {{ $profile->street ?: 'Street not provided' }}<br>
                                    {{ $profile->barangay ?: 'Barangay not provided' }}<br>
                                    {{ $profile->municipality ?: 'Municipality not provided' }}<br>
                                    {{ $profile->province ?: 'Province not provided' }}
                                </p>

                                @if($profile->company_type_id && $profile->companyType)
                                    <h6><i class="fas fa-tag me-1"></i>Service Category</h6>
                                    <p class="text-muted">{{ $profile->companyType->name }}</p>
                                @endif
                            @else
                                <p class="text-muted">No information available</p>
                            @endif
                        </div>
                    </div>

                    @if($profile && $profile->company_description)
                        <div class="mt-3">
                            <h6><i class="fas fa-align-left me-1"></i>About</h6>
                            <p class="text-muted">{{ $profile->company_description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Service Statistics -->
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h2 class="text-primary">{{ $user->jobs()->count() }}</h2>
                    <p class="mb-0 text-muted">Service Requests Posted</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    <h2 class="text-success">{{ $user->jobs()->where('status', 'open')->count() }}</h2>
                    <p class="mb-0 text-muted">Active Service Listings</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-light">
                <div class="card-body text-center">
                    @php
                        $totalApplications = 0;
                        foreach($user->jobs as $job) {
                            $totalApplications += $job->jobApplications()->count();
                        }
                    @endphp
                    <h2 class="text-info">{{ $totalApplications }}</h2>
                    <p class="mb-0 text-muted">Worker Applications</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Requirements -->
    @if($profile && $profile->company_description)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-home me-2"></i>Household Service Needs</h5>
            </div>
            <div class="card-body">
                <p class="text-muted">{{ $profile->company_description }}</p>
            </div>
        </div>
    @endif

    <!-- Recent Service Requests -->
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Recent Service Requests</h5>
        </div>
        <div class="card-body">
            @if($user->jobs()->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Job Title</th>
                                <th>Employment Type</th>
                                <th>Status</th>
                                <th>Posted</th>
                                <th>Applications</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($user->jobs()->latest()->take(5)->get() as $job)
                                <tr>
                                    <td>
                                        <strong>{{ $job->job_title }}</strong>
                                        <br><small class="text-muted">{{ Str::limit($job->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $job->status === 'open' ? 'success' : ($job->status === 'closed' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $job->created_at ? $job->created_at->diffForHumans() : 'Unknown' }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $job->jobApplications()->count() }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center mt-3">
                    <a href="{{ route('employers.jobs.index') }}" class="btn btn-outline-primary">
                        <i class="fas fa-list me-1"></i>View All Jobs
                    </a>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                    <p class="text-muted">You haven't posted any jobs yet.</p>
                    <a href="{{ route('employers.jobs.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>Post Your First Job
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection