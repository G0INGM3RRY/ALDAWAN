@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row">
        <!-- Application Header -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 text-dark">Gig Application Review</h5>
                            <small class="text-muted">Verify worker credentials and qualifications</small>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-secondary">
                                Status: {{ ucfirst($application->status) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h4 class="text-dark">{{ $application->job->job_title }}</h4>
                            <p class="text-muted mb-2">{{ $application->job->classification }}</p>
                            <small class="text-muted">
                                Applied on: {{ $application->applied_at->format('F j, Y \a\t g:i A') }}
                            </small>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('employers.viewapplications', $application->job->id) }}" class="btn btn-outline-secondary btn-sm">
                                ← Back to Applications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Worker Profile Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 text-dark">Worker Profile</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px; font-size: 20px;">
                            {{ strtoupper(substr($application->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $application->user->name }}</h5>
                            <small class="text-muted">{{ $application->user->email }}</small>
                        </div>
                    </div>

                    @if($application->user->jobseekerProfile)
                        <div class="row g-2">
                            <div class="col-12">
                                <strong class="text-muted">Work Type:</strong>
                                <p class="mb-2">{{ $application->user->jobseekerProfile->professional_title ?? 'Not specified' }}</p>
                            </div>
                            <div class="col-12">
                                <strong class="text-muted">Location:</strong>
                                <p class="mb-2">{{ $application->user->jobseekerProfile->location ?? 'Not specified' }}</p>
                            </div>
                            <div class="col-12">
                                <strong class="text-muted">Skills:</strong>
                                <p class="mb-2">{{ $application->user->jobseekerProfile->skills ?? 'Not specified' }}</p>
                            </div>
                            <div class="col-6">
                                <strong class="text-muted">Experience:</strong>
                                <p class="mb-2">{{ $application->user->jobseekerProfile->experience_years ?? 'Not specified' }} years</p>
                            </div>
                            <div class="col-6">
                                <strong class="text-muted">Rate:</strong>
                                <p class="mb-2">{{ $application->user->jobseekerProfile->hourly_rate ?? 'Negotiable' }}</p>
                            </div>
                        </div>

                        @if($application->user->jobseekerProfile->bio)
                            <div class="mt-3">
                                <strong class="text-muted">About Worker:</strong>
                                <p class="small">{{ $application->user->jobseekerProfile->bio }}</p>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-warning">
                            <small>Worker profile is incomplete</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Application Details -->
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 text-dark">Application Details</h6>
                </div>
                <div class="card-body">
                    <!-- Cover Message -->
                    @if($application->cover_letter)
                        <div class="mb-4">
                            <strong class="text-muted">Why They Want This Gig:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                <p class="mb-0 small">{{ $application->cover_letter }}</p>
                            </div>
                        </div>
                    @else
                        <div class="mb-4">
                            <strong class="text-muted">Application Message:</strong>
                            <p class="text-muted small">No message provided</p>
                        </div>
                    @endif

                    <!-- Application Timeline -->
                    <div class="mb-3">
                        <strong class="text-muted">Timeline:</strong>
                        <div class="mt-2">
                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                <span class="small">Applied</span>
                                <span class="small text-muted">{{ $application->applied_at->format('M j, Y') }}</span>
                            </div>
                            @if($application->reviewed_at)
                                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                    <span class="small">Reviewed</span>
                                    <span class="small text-muted">{{ $application->reviewed_at->format('M j, Y') }}</span>
                                </div>
                            @endif
                            @if($application->status_updated_at && $application->status !== 'pending')
                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <span class="small">Status Updated</span>
                                    <span class="small text-muted">{{ $application->status_updated_at->format('M j, Y') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Section -->
        <div class="col-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 text-dark">📄 Worker Documentation</h6>
                    <small class="text-muted">Verify credentials and work samples</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Resume/Portfolio -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-dark">Resume/Portfolio</h6>
                            @if($application->resume_file_path)
                                <div class="border rounded p-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="text-dark">📄 {{ basename($application->resume_file_path) }}</span>
                                            <br>
                                            <small class="text-muted">
                                                Size: {{ number_format(Storage::disk('public')->size($application->resume_file_path) / 1024, 1) }} KB
                                            </small>
                                        </div>
                                        <div>
                                            <a href="{{ Storage::url($application->resume_file_path) }}" target="_blank" class="btn btn-dark btn-sm">
                                                View
                                            </a>
                                            <a href="{{ Storage::url($application->resume_file_path) }}" download class="btn btn-outline-dark btn-sm">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-light border">
                                    <small class="text-muted">No portfolio uploaded</small>
                                </div>
                            @endif
                        </div>

                        <!-- Certificates & Work Samples -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-dark">Certificates & Work Samples</h6>
                            @if($application->additional_documents && count($application->additional_documents) > 0)
                                <div class="border rounded p-3 bg-light">
                                    @foreach($application->additional_documents as $document)
                                        <div class="d-flex justify-content-between align-items-center mb-2 pb-2 border-bottom">
                                            <div>
                                                <span class="text-dark">📄 {{ basename($document) }}</span>
                                                <br>
                                                <small class="text-muted">
                                                    Size: {{ number_format(Storage::disk('public')->size($document) / 1024, 1) }} KB
                                                </small>
                                            </div>
                                            <div>
                                                <a href="{{ Storage::url($document) }}" target="_blank" class="btn btn-dark btn-sm">
                                                    View
                                                </a>
                                                <a href="{{ Storage::url($document) }}" download class="btn btn-outline-dark btn-sm">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="alert alert-light border">
                                    <small class="text-muted">No certificates or work samples uploaded</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons and Notes -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h6 class="mb-0 text-dark">Worker Assessment & Actions</h6>
                </div>
                <div class="card-body">
                    <!-- Current Notes -->
                    @if($application->employer_notes)
                        <div class="mb-4">
                            <h6 class="text-muted">Your Notes:</h6>
                            <div class="p-3 bg-light rounded border">
                                <p class="mb-0 small">{{ $application->employer_notes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    @if($application->status === 'pending' || $application->status === 'under_review')
                        <div class="row g-3">
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('employers.applications.accept', $application->id) }}">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Hire this worker?')">
                                        Hire Worker
                                    </button>
                                </form>
                            </div>
                            <div class="col-md-6">
                                <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    Decline Application
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-light border">
                            <strong>Application Status:</strong> {{ ucfirst($application->status) }}
                            @if($application->rejection_reason)
                                <br><strong>Reason:</strong> {{ $application->rejection_reason }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('employers.applications.reject', $application->id) }}">
                @csrf
                @method('PATCH')
                <div class="modal-header border-bottom">
                    <h5 class="modal-title text-dark">Decline Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Decline application from <strong>{{ $application->user->name }}</strong>?</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason <span class="text-muted">(Optional)</span></label>
                        <textarea class="form-control" name="rejection_reason" rows="3" placeholder="Provide feedback to help the worker..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Decline Application</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
