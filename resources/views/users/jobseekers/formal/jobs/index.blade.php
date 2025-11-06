@extends('layouts.dashboard')

@section('content')
<div class="mt-4">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-primary"><i class="fas fa-search me-2"></i>Browse Jobs</h1>
                <p class="text-muted mb-0">{{ $jobs->count() }} job{{ $jobs->count() !== 1 ? 's' : '' }} available</p>
            </div>

            <!-- Search and Filter Section -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0"><i class="fas fa-filter me-2"></i>Search & Filter Jobs</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('jobs.index') }}" id="jobSearchForm">
                        <div class="row g-3">
                            <!-- Search Keyword -->
                            <div class="col-md-6">
                                <label for="search" class="form-label fw-semibold">Search Keywords</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" id="search" name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="Job title, company, or keywords...">
                                </div>
                            </div>

                            <!-- Location -->
                            <div class="col-md-6">
                                <label for="location" class="form-label fw-semibold">Location</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    <input type="text" class="form-control" id="location" name="location" 
                                           value="{{ request('location') }}" 
                                           placeholder="City, province, or region...">
                                </div>
                            </div>

                            <!-- Job Classification -->
                            <div class="col-md-4">
                                <label for="classification" class="form-label fw-semibold">Job Category</label>
                                <select class="form-select" id="classification" name="classification">
                                    <option value="">All Categories</option>
                                    @if(isset($classifications))
                                        @foreach($classifications as $classification)
                                            <option value="{{ $classification->id }}" 
                                                    {{ request('classification') == $classification->id ? 'selected' : '' }}>
                                                {{ $classification->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <!-- Employment Type -->
                            <div class="col-md-4">
                                <label for="employment_type" class="form-label fw-semibold">Employment Type</label>
                                <select class="form-select" id="employment_type" name="employment_type">
                                    <option value="">All Types</option>
                                    <option value="full_time" {{ request('employment_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part_time" {{ request('employment_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="temporary" {{ request('employment_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                    <option value="internship" {{ request('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                </select>
                            </div>

                            <!-- Sort By -->
                            <div class="col-md-4">
                                <label for="sort" class="form-label fw-semibold">Sort By</label>
                                <select class="form-select" id="sort" name="sort">
                                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Newest First</option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                                    <option value="salary_high" {{ request('sort') == 'salary_high' ? 'selected' : '' }}>Salary: High to Low</option>
                                    <option value="salary_low" {{ request('sort') == 'salary_low' ? 'selected' : '' }}>Salary: Low to High</option>
                                </select>
                            </div>

                            <!-- Salary Range -->
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Salary Range (PHP)</label>
                                <div class="row g-2">
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="min_salary" 
                                               value="{{ request('min_salary') }}" 
                                               placeholder="Min" min="0" step="1000">
                                    </div>
                                    <div class="col-6">
                                        <input type="number" class="form-control" name="max_salary" 
                                               value="{{ request('max_salary') }}" 
                                               placeholder="Max" min="0" step="1000">
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="col-md-6 d-flex align-items-end">
                                <div class="w-100 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="fas fa-search me-1"></i>Search Jobs
                                    </button>
                                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if(request()->hasAny(['search', 'location', 'classification', 'employment_type', 'min_salary', 'max_salary', 'sort']))
            <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
                <i class="fas fa-info-circle me-2"></i>
                <div class="flex-grow-1">
                    <strong>Active Filters:</strong>
                    @if(request('search'))
                        <span class="badge bg-primary me-1">Search: {{ request('search') }}</span>
                    @endif
                    @if(request('location'))
                        <span class="badge bg-primary me-1">Location: {{ request('location') }}</span>
                    @endif
                    @if(request('classification') && isset($classifications))
                        <span class="badge bg-primary me-1">Category: {{ $classifications->find(request('classification'))->name ?? 'N/A' }}</span>
                    @endif
                    @if(request('employment_type'))
                        <span class="badge bg-primary me-1">Type: {{ ucfirst(str_replace('_', ' ', request('employment_type'))) }}</span>
                    @endif
                    @if(request('min_salary') || request('max_salary'))
                        <span class="badge bg-primary me-1">
                            Salary: PHP{{ request('min_salary') ? number_format(request('min_salary')) : '0' }} - 
                            PHP{{ request('max_salary') ? number_format(request('max_salary')) : '∞' }}
                        </span>
                    @endif
                </div>
                <a href="{{ route('jobs.index') }}" class="btn btn-sm btn-outline-primary">Clear All</a>
            </div>
            @endif

            @if($jobs->count() > 0)
                <!-- Match indicator message -->
                @auth
                    @if(Auth::user()->jobseekerProfile && !request()->has('disable_matching'))
                        <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                            <i class="fas fa-magic me-2"></i>
                            <div>
                                <strong>Personalized Results:</strong> Jobs are ranked by how well they match your skills, preferences, and profile.
                                Higher match percentages indicate better fit!
                            </div>
                        </div>
                    @endif
                @endauth

                <div class="row">
                    @foreach($jobs as $job)
                        <div class="col-md-6 col-lg-4 mb-4">
                            <div class="card h-100 shadow-sm {{ isset($job->match_score) && $job->match_score >= 80 ? 'border-success' : '' }}">
                                <!-- Match Score Badge -->
                                @if(isset($job->match_score))
                                    <div class="position-absolute top-0 end-0 m-2">
                                        @if($job->match_score >= 80)
                                            <span class="badge bg-success">
                                                <i class="fas fa-star me-1"></i>{{ number_format($job->match_score, 0) }}% Match
                                            </span>
                                        @elseif($job->match_score >= 60)
                                            <span class="badge bg-info">
                                                {{ number_format($job->match_score, 0) }}% Match
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ number_format($job->match_score, 0) }}% Match
                                            </span>
                                        @endif
                                    </div>
                                @endif

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
                                            <span class="badge bg-{{ $job->job_type === 'formal' ? 'primary' : 'success' }}">
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
