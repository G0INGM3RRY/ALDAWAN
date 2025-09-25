@extends('layouts.dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="#">Search</a>
        <a href="{{ route('jobs.informal') }}" class="btn btn-primary">Browse Informal Jobs</a>
    </div>
    
    <h1>Recent Job Opportunities</h1>
    <div class="row">
        @php
            // Get recent informal jobs (filter by job_type, not employer_type)
            $recentJobs = App\Models\Jobs::with(['user.employerProfile'])
                ->where('job_type', 'informal')
                ->where('status', 'open')
                ->latest()
                ->take(6)
                ->get();
        @endphp
        
        @if($recentJobs->count() > 0)
            @foreach($recentJobs as $job)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $job->job_title }}</h5>
                            <h6 class="card-subtitle mb-2 text-muted">
                                @if($job->user && $job->user->employerProfile && $job->user->employerProfile->company_name)
                                    {{ $job->user->employerProfile->company_name }}
                                @else
                                    Company Name
                                @endif
                            </h6>
                            
                            <div class="mb-2">
                                @if($job->location)
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt"></i> {{ $job->location }}
                                    </small>
                                @endif
                            </div>
                            
                            <div class="mb-2">
                                @if($job->employment_type)
                                    <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                                @endif
                                @if($job->classification)
                                    <span class="badge bg-secondary">{{ $job->classification }}</span>
                                @endif
                            </div>

                            @if($job->salary)
                                <div class="mb-2">
                                    <small class="text-success fw-bold">
                                        ₱{{ number_format($job->salary) }} minimum
                                    </small>
                                </div>
                            @endif

                            <p class="card-text">
                                {{ Str::limit($job->description, 100) }}
                            </p>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        @if($job->created_at)
                                            Posted {{ $job->created_at->diffForHumans() }}
                                        @else
                                            Recently posted
                                        @endif
                                    </small>
                                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-briefcase fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No Job Opportunities Available</h4>
                    <p class="text-muted">There are currently no informal job opportunities available. Please check back later or browse all jobs.</p>
                    <a href="{{ route('jobs.informal') }}" class="btn btn-primary">Browse Jobs</a>
                </div>
            </div>
        @endif
    </div>

    @if($recentJobs->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('jobs.informal') }}" class="btn btn-outline-primary">View All Informal Jobs</a>
        </div>
    @endif
@endsection
