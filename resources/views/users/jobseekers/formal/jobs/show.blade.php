@extends('layouts.dashboard')

@section('content')
<div class="row">
    <div class="col-md-12">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">
                ← Back to Job Listings
                </a>
            </div>
            
            <!-- Job Details Card -->
            <div class="card">
                <div class="card-body">
                    <!-- Job Header -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h2 class="mb-2">{{ $job->job_title }}</h2>
                            <h6 class="text-muted">{{ $job->jobClassification->name ?? 'Not specified' }}</h6>
                        </div>
                        <span class="badge bg-{{ $job->status == 'open' ? 'success' : 'secondary' }} fs-6">
                            {{ ucfirst($job->status) }}
                        </span>
                    </div>
                    
                    <!-- Job Info Row -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <small class="text-muted">Location</small>
                            <div><strong>{{ $job->location }}</strong></div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Employment Type</small>
                            <div><strong>{{ ucfirst($job->employment_type) }}</strong></div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Salary</small>
                            <div><strong>₱{{ number_format($job->salary, 2) }}</strong></div>
                        </div>
                        <div class="col-md-3">
                            <small class="text-muted">Posted</small>
                            <div><strong>{{ $job->created_at->format('M d, Y') }}</strong></div>
                        </div>
                    </div>
                    
                    <!-- Apply Button -->
                    @auth
                        @if(auth()->user()->role === 'seeker' && $job->status === 'open')
                            @php
                                $hasApplied = $job->hasAppliedBy(auth()->id());
                            @endphp
                            
                            <div class="mb-4">
                                @if($hasApplied)
                                    <div class="alert alert-success d-flex align-items-center" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <div>
                                            <strong>Application Submitted!</strong> You have already applied for this position.
                                            <a href="{{ route('jobseekers.applications') }}" class="alert-link">View your applications</a>
                                        </div>
                                    </div>
                                @else
                                    <a href="{{ route('jobs.apply', $job->id) }}" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Apply Now
                                    </a>
                                    <button class="btn btn-outline-secondary btn-lg ms-2">
                                        <i class="fas fa-bookmark me-2"></i>Save Job
                                    </button>
                                @endif
                            </div>
                        @elseif(auth()->user()->role === 'seeker' && $job->status !== 'open')
                            <div class="alert alert-warning mb-4">
                                <strong>Applications Closed</strong> - This job is {{ $job->status === 'filled' ? 'filled' : 'no longer accepting applications' }}.
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info mb-4">
                            <strong>Interested?</strong> 
                            <a href="{{ route('login') }}">Login</a> or 
                            <a href="{{ route('register') }}">Register</a> to apply.
                        </div>
                    @endauth
                    
                    <!-- Job Description -->
                    <div class="mb-4">
                        <h5>Job Description</h5>
                        <div class="border p-3 rounded bg-light">
                            {{ $job->description }}
                        </div>
                    </div>
                    
                    <!-- Job Requirements -->
                    <div class="mb-4">
                        <h5>Requirements</h5>
                        <div class="border p-3 rounded bg-light">
                            {{ $job->requirements }}
                        </div>
                    </div>
                    
                    <!-- Company Info -->
                    <div>
                        <h5>Company Information</h5>
                        <div class="border p-3 rounded bg-light">
                            <strong>Company:</strong> {{ $job->user->employerProfile->company_name ?? 'Company Name' }}<br>
                            <strong>Classification:</strong> {{ $job->classification }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
