@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12">
            <h1 class="h3 text-info fw-bold">My Applications</h1>
            <p class="text-muted">Track your gig applications and their status</p>
        </div>
    </div>

    <div class="row">
        @forelse($applications as $application)
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <!-- Application Status Badge -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <span class="badge bg-{{ $application->status == 'pending' ? 'warning' : ($application->status == 'accepted' ? 'success' : ($application->status == 'rejected' ? 'danger' : 'secondary')) }} fs-6">
                            {{ ucfirst($application->status) }}
                        </span>
                        <small class="text-muted">{{ $application->applied_at->format('M d, Y') }}</small>
                    </div>

                    <!-- Job Details -->
                    <div class="mb-3">
                        <h5 class="fw-bold text-dark mb-2">{{ $application->job->job_title }}</h5>
                        <h6 class="text-info mb-2">{{ $application->job->classification }}</h6>
                        
                        <!-- Job Info -->
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <small class="text-muted">
                                    <span class="fw-semibold">Company:</span> 
                                    {{ $application->job->user->employerProfile->company_name ?? 'Company Name' }}
                                </small>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">
                                    <span class="fw-semibold">Location:</span> 
                                    {{ $application->job->location }}
                                </small>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">
                                    <span class="fw-semibold">Pay:</span> 
                                    ₱{{ number_format($application->job->salary, 2) }}
                                </small>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">
                                    <span class="fw-semibold">Type:</span> 
                                    {{ ucfirst($application->job->employment_type) }}
                                </small>
                            </div>
                        </div>

                        <!-- Application Details -->
                        @if($application->cover_letter)
                        <div class="mb-3">
                            <small class="text-muted fw-semibold d-block mb-1">Your Message:</small>
                            <p class="small text-muted mb-0 bg-light p-2 rounded">
                                {{ Str::limit($application->cover_letter, 120) }}
                            </p>
                        </div>
                        @endif
                    </div>

                    <!-- Application Status Info -->
                    <div class="mb-3 p-2 rounded" style="background-color: #f8f9fa;">
                        @if($application->status == 'pending')
                            <small class="text-muted">
                                <span class="fw-semibold">Status:</span> Under review by employer
                            </small>
                        @elseif($application->status == 'accepted')
                            <small class="text-success">
                                <span class="fw-semibold">Congratulations!</span> Your application was accepted
                            </small>
                        @elseif($application->status == 'rejected')
                            <small class="text-danger">
                                <span class="fw-semibold">Application declined.</span> 
                                @if($application->rejection_reason)
                                    <br>Reason: {{ $application->rejection_reason }}
                                @endif
                            </small>
                        @endif
                        
                        @if($application->reviewed_at)
                        <br><small class="text-muted">Reviewed {{ $application->reviewed_at->diffForHumans() }}</small>
                        @endif
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2">
                        <a href="{{ route('jobs.show', $application->job->id) }}" class="btn btn-sm btn-outline-warning flex-fill">
                            View Gig
                        </a>
                        <a href="{{ route('jobseekers.applications.show', $application->id) }}" class="btn btn-sm btn-outline-primary flex-fill">
                            Details
                        </a>
                        @if($application->status == 'pending')
                        <form method="POST" action="{{ route('jobseekers.applications.withdraw', $application->id) }}" class="flex-fill">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('Are you sure you want to withdraw this application?')">
                                Withdraw
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @empty
        <!-- No Applications Message -->
        <div class="col-12">
            <div class="text-center py-5">
                <div class="mb-4">
                    <div class="text-info display-1" style="opacity: 0.3;">📋</div>
                </div>
                <h5 class="text-muted">No applications yet</h5>
                <p class="text-muted">Start applying for gigs to see your applications here.</p>
                <a href="{{ route('jobs.index') }}" class="btn btn-warning">
                    Browse Gigs
                </a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($applications->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $applications->links() }}
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
