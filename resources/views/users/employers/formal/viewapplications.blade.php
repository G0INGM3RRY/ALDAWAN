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
            <h1 class="h3 text-primary fw-bold">Job Applications</h1>
            <p class="text-muted">Review applications for your posted positions</p>
        </div>
    </div>

    <div class="row">
        <!-- Job Details - Left Side -->
        <div class="col-lg-4 mb-4">
            <div class="card border-primary shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Position Details</h5>
                </div>
                <div class="card-body">
                    <!-- Job Title and Status -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="fw-bold text-dark">{{ $job->job_title ?? 'Sample Position Title' }}</h5>
                        <span class="badge bg-success">{{ ucfirst($job->status ?? 'active') }}</span>
                    </div>
                    
                    <!-- Job Type Badge -->
                    <div class="mb-3">
                        <span class="badge bg-primary">{{ ucfirst($job->job_type ?? 'formal') }} Position</span>
                    </div>
                    
                    <!-- Job Classification -->
                    <h6 class="text-primary fw-semibold mb-2">{{ $job->jobClassification->name ?? 'Professional Services' }}</h6>
                    
                    <!-- Description -->
                    <p class="card-text text-muted mb-3">
                        {{ Str::limit($job->description ?? 'Sample job description for this position posting.', 120) }}
                    </p>
                    
                    <!-- Job Details -->
                    <div class="mb-3">
                        <div class="row g-2">
                            <div class="col-12">
                                <small class="text-muted">
                                    Location: {{ $job->location ?? 'Location not specified' }}
                                </small>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">
                                    Type: {{ ucfirst($job->employment_type ?? 'full-time') }}
                                </small>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">
                                    Salary: ₱{{ number_format($job->salary ?? 25000, 2) }}
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Posted Date and Actions -->
                    <div class="d-flex justify-content-between align-items-center mt-3 pt-3 border-top">
                        <small class="text-muted">
                            Posted: {{ $job->posted_at ? $job->posted_at->format('M d, Y') : 'Jan 01, 2024' }}
                        </small>
                        
                        <div class="btn-group" role="group">
                            <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmDelete()">Delete</button>
                        </div>
                    </div>

                    <!-- Applications Count -->
                    <div class="mt-3 p-2 bg-light rounded">
                        <div class="text-center">
                            <h4 class="text-primary mb-0">{{ $applications->count() ?? 0 }}</h4>
                            <small class="text-muted">Total Applications</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Applicants - Right Side -->
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-primary fw-bold mb-0">Candidates</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary active">All</button>
                    <button type="button" class="btn btn-sm btn-outline-primary">Pending</button>
                    <button type="button" class="btn btn-sm btn-outline-primary">Reviewed</button>
                    <button type="button" class="btn btn-sm btn-outline-primary">Accepted</button>
                </div>
            </div>

            <!-- Applicants Grid -->
            <div class="row">
                @forelse($applications ?? [] as $application)
                <div class="col-md-6 mb-3">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body">
                            <!-- Applicant Header -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                        {{ substr($application->user->name ?? 'J', 0, 1) }}
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $application->user->name ?? 'Jane Smith' }}</h6>
                                        <small class="text-muted">Applied {{ $application->applied_at ? $application->applied_at->diffForHumans() : '2 days ago' }}</small>
                                    </div>
                                </div>
                                <span class="badge bg-{{ $application->status == 'pending' ? 'warning' : ($application->status == 'accepted' ? 'success' : 'secondary') }}">
                                    {{ ucfirst($application->status ?? 'pending') }}
                                </span>
                            </div>

                            <!-- Cover Letter Preview -->
                            @if($application->cover_letter ?? false)
                            <div class="mb-3">
                                <small class="text-muted fw-semibold d-block mb-1">Cover Letter:</small>
                                <p class="small text-muted mb-0">
                                    {{ Str::limit($application->cover_letter, 100) }}
                                </p>
                            </div>
                            @endif

                            <!-- Application Details -->
                            <div class="mb-3">
                                @if($application->resume_file_path ?? false)
                                <small class="d-block mb-1">
                                    <a href="#" class="text-decoration-none">Resume attached</a>
                                </small>
                                @endif
                                @if($application->additional_documents ?? false)
                                <small class="d-block mb-1">Additional documents</small>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('employers.applications.view', $application->id) }}" class="btn btn-sm btn-outline-primary flex-fill">View</a>
                                @if(($application->status ?? 'pending') == 'pending')
                                <form method="POST" action="{{ route('employers.applications.accept', $application->id) }}" class="flex-fill">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-success w-100">Accept</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger flex-fill" onclick="showRejectModal({{ $application->id }})">Reject</button>
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
                            <div class="text-primary display-1" style="opacity: 0.3;">📥</div>
                        </div>
                        <h5 class="text-muted">No applications yet</h5>
                        <p class="text-muted">Applications for this position will appear here once candidates start applying.</p>
                        <a href="{{ route('jobs.show', $job->id ?? 1) }}" class="btn btn-outline-primary">Share Position</a>
                    </div>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<form id="deleteForm" method="POST" action="#" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Reject Application Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="rejectForm" method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Reject Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Reason for rejection (optional):</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" placeholder="Provide feedback to the candidate..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Application</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this position? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}

function showRejectModal(applicationId) {
    const form = document.getElementById('rejectForm');
    form.action = `/employers/applications/${applicationId}/reject`;
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
}
</script>

@endsection