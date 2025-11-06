@extends('layouts.dashboard')

@section('content')
    <!-- Quick Search Section -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <form method="GET" action="{{ route('jobs.index') }}">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-primary"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 ps-0" name="search" 
                                   placeholder="Search jobs by title, company, or keywords..." 
                                   value="{{ request('search') }}" autocomplete="off">
                            <button type="submit" class="btn btn-primary px-4">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <a href="{{ route('jobs.formal') }}" class="btn btn-outline-primary">
                        <i class="fas fa-briefcase me-2"></i>Browse All Jobs
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Jobs Section -->
    @php
        $recommendationService = app(\App\Services\JobRecommendationService::class);
        $recommendedJobs = $recommendationService->getTopRecommendations(Auth::user(), 6);
    @endphp

    @if($recommendedJobs->count() > 0)
        <div class="mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="mb-0">
                    <i class="fas fa-star text-warning me-2"></i>Recommended for You
                </h2>
                <span class="badge bg-success">Based on your profile</span>
            </div>
            
            <div class="row">
                @foreach($recommendedJobs as $job)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100 shadow-sm border-success">
                            <!-- Match Score Badge -->
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-success">
                                    <i class="fas fa-star me-1"></i>{{ number_format($job->match_score, 0) }}% Match
                                </span>
                            </div>
                            
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
                                            <i class="fas fa-map-marker-alt me-1"></i>{{ $job->location }}
                                        </small>
                                    @endif
                                </div>
                                
                                <p class="card-text flex-grow-1">
                                    {{ Str::limit($job->description, 100) }}
                                </p>
                                
                                <div class="mt-auto">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $job->employment_type)) }}</span>
                                        <strong class="text-success">₱{{ number_format($job->salary, 2) }}</strong>
                                    </div>
                                    <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-primary w-100">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <h1>Recent Jobs</h1>
    <div class="row">
        @php
            // Get recent formal jobs (filter by job_type, not employer_type)
            $recentJobs = App\Models\Jobs::with(['user.employerProfile'])
                ->where('job_type', 'formal')
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
                                @if($job->jobClassification)
                                    <span class="badge bg-secondary">{{ $job->jobClassification->name }}</span>
                                @endif
                            </div>

                            @if($job->salary)
                                <div class="mb-2">
                                    <small class="text-success fw-bold">
                                        PHP{{ number_format($job->salary) }} minimum
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
                    <h4 class="text-muted">No Jobs Available</h4>
                    <p class="text-muted">Check back later for new job opportunities.</p>
                    <a href="{{ route('jobs.formal') }}" class="btn btn-primary">Browse Jobs</a>
                </div>
            </div>
        @endif
    </div>

    @if($recentJobs->count() > 0)
        <div class="text-center mt-4">
            <a href="{{ route('jobs.formal') }}" class="btn btn-outline-primary">View All Formal Jobs</a>
        </div>
    @endif
@endsection
