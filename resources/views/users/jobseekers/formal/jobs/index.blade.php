@extends('layouts.dashboard')

@section('content')
<div class="mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3">Browse Jobs</h1>
                <p class="text-muted mb-0">{{ $jobs->count() }} job{{ $jobs->count() !== 1 ? 's' : '' }} available</p>
            </div>

            @if($jobs->count() > 0)
                <div class="row">
                    @foreach($jobs as $job)
                        <div class="col-md-6 col-lg-4 mb-4">
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
                                        @if($job->jobClassification)
                                            <span class="badge bg-secondary">{{ $job->jobClassification->name }}</span>
                                        @endif
                                        @if($job->job_type)
                                            <span class="badge bg-{{ $job->job_type === 'formal' ? 'success' : 'warning' }}">
                                                {{ ucfirst($job->job_type) }} Position
                                            </span>
                                        @endif
                                    </div>

                                    @if($job->salary)
                                        <div class="mb-2">
                                            <small class="text-success fw-bold">
                                                ${{ number_format($job->salary) }} per year
                                            </small>
                                        </div>
                                    @endif

                                    <p class="card-text">
                                        {{ Str::limit($job->description, 100) }}
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                Posted {{ $job->created_at ? $job->created_at->diffForHumans() : 'Recently' }}
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
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-briefcase fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No Jobs Available</h4>
                    <p class="text-muted">Check back later for new job opportunities.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
