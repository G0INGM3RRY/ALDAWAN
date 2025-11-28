@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shield-check me-2"></i>Verification Management
        </h1>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Verifications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_pending'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Approved</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_approved'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_rejected'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Verifications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_all'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-file-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.verifications') }}" class="row g-3">
                <div class="col-md-5">
                    <label for="search" class="form-label">
                        <i class="fas fa-search me-1"></i>Search
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           placeholder="Search by name, email, or company..." 
                           value="{{ request('search') }}">
                    <small class="text-muted">Search in names, emails, and company names</small>
                </div>
                
                <div class="col-md-3">
                    <label for="status" class="form-label">
                        <i class="fas fa-filter me-1"></i>Status
                    </label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>
                            Approved
                        </option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>
                            Rejected
                        </option>
                    </select>
                </div>
                
                <div class="col-md-4">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.verifications') }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Verifications Tabs -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#company-tab" type="button" role="tab">
                        <i class="fas fa-building me-1"></i>Formal Employers
                        <span class="badge bg-primary ms-1">{{ $stats['company_total'] }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#informal-employer-tab" type="button" role="tab">
                        <i class="fas fa-home me-1"></i>Informal Employers
                        <span class="badge bg-success ms-1">{{ $stats['informal_employer_total'] }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#formal-jobseeker-tab" type="button" role="tab">
                        <i class="fas fa-user-tie me-1"></i>Formal Jobseekers
                        <span class="badge bg-info ms-1">{{ $stats['formal_jobseeker_total'] }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#informal-jobseeker-tab" type="button" role="tab">
                        <i class="fas fa-users me-1"></i>Informal Jobseekers
                        <span class="badge bg-warning ms-1">{{ $stats['informal_jobseeker_total'] }}</span>
                    </button>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content">
                <!-- Formal Employers (Company) Tab -->
                <div class="tab-pane fade show active" id="company-tab" role="tabpanel">
                    @if($companyVerifications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Company Name</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Registration #</th>
                                <th>TIN</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($companyVerifications as $verification)
                                <tr>
                                    <td>{{ $verification->id }}</td>
                                    <td>
                                        <strong>{{ $verification->employer->company_name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $verification->employer->user->name ?? 'N/A' }}</td>
                                    <td>{{ $verification->employer->user->email ?? 'N/A' }}</td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $verification->business_registration_number ?? 'Not provided' }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $verification->tax_id ?? 'Not provided' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($verification->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Approved
                                            </span>
                                        @elseif($verification->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @elseif($verification->status === 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($verification->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $verification->submitted_at ? $verification->submitted_at->format('M d, Y') : 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View User Profile -->
                                            <a href="{{ route('admin.users.show', $verification->employer->user_id) }}" 
                                               class="btn btn-info" 
                                               title="View User Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($verification->status === 'pending')
                                                <!-- Approve Button -->
                                                <form action="{{ route('admin.verifications.approve', $verification) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Approve this verification?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <!-- Reject Button -->
                                                <button type="button" 
                                                        class="btn btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectModal{{ $verification->id }}"
                                                        title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif

                                            <!-- View Document -->
                                            @if($verification->verification_document_path)
                                                <a href="{{ route('admin.verifications.download', $verification) }}" 
                                                   target="_blank" 
                                                   class="btn btn-primary" 
                                                   title="Download Document">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                        </div>

                                        <!-- Reject Modal -->
                                        <div class="modal fade" id="rejectModal{{ $verification->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.verifications.reject', $verification) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Verification</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Company: <strong>{{ $verification->employer->company_name }}</strong></p>
                                                            <div class="mb-3">
                                                                <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                                <textarea name="rejection_reason" 
                                                                          class="form-control" 
                                                                          rows="4" 
                                                                          required
                                                                          placeholder="Please provide a clear reason for rejection..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-times me-1"></i>Reject Verification
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Links -->
                <div class="mt-3">
                    {{ $companyVerifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No formal employer verifications found.</p>
                </div>
            @endif
        </div>

        <!-- Informal Employers Tab -->
        <div class="tab-pane fade" id="informal-employer-tab" role="tabpanel">
            @if($informalEmployerVerifications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Household Name</th>
                                <th>Contact Person</th>
                                <th>Email</th>
                                <th>Documents</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($informalEmployerVerifications as $verification)
                                <tr>
                                    <td>{{ $verification->id }}</td>
                                    <td>
                                        <strong>{{ $verification->employer->company_name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $verification->employer->user->name ?? 'N/A' }}</td>
                                    <td>{{ $verification->employer->user->email ?? 'N/A' }}</td>
                                    <td>
                                        <small>
                                            @if($verification->valid_id_path)
                                                <span class="badge bg-success">✓ ID</span>
                                            @endif
                                            @if($verification->proof_of_address_path)
                                                <span class="badge bg-success">✓ Address</span>
                                            @endif
                                            @if($verification->barangay_clearance_path)
                                                <span class="badge bg-success">✓ Clearance</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($verification->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Approved
                                            </span>
                                        @elseif($verification->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @elseif($verification->status === 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($verification->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $verification->submitted_at ? $verification->submitted_at->format('M d, Y') : 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View User Profile -->
                                            <a href="{{ route('admin.users.show', $verification->employer->user_id) }}" 
                                               class="btn btn-info" 
                                               title="View User Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($verification->status === 'pending')
                                                <!-- Approve Button -->
                                                <form action="{{ route('admin.verifications.informal-employer.approve', $verification) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Approve this verification?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <!-- Reject Button -->
                                                <button type="button" 
                                                        class="btn btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectInformalEmployerModal{{ $verification->id }}"
                                                        title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif

                                            <!-- View Documents Dropdown -->
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" title="View Documents">
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($verification->valid_id_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.informal-employer.download', [$verification, 'valid_id']) }}" target="_blank"><i class="fas fa-id-card me-1"></i> Valid ID</a></li>
                                                    @endif
                                                    @if($verification->proof_of_address_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.informal-employer.download', [$verification, 'proof_of_address']) }}" target="_blank"><i class="fas fa-home me-1"></i> Proof of Address</a></li>
                                                    @endif
                                                    @if($verification->barangay_clearance_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.informal-employer.download', [$verification, 'barangay_clearance']) }}" target="_blank"><i class="fas fa-certificate me-1"></i> Barangay Clearance</a></li>
                                                    @endif
                                                    @if(!$verification->valid_id_path && !$verification->proof_of_address_path && !$verification->barangay_clearance_path)
                                                        <li><span class="dropdown-item text-muted">No documents</span></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Reject Modal for Informal Employer -->
                                        <div class="modal fade" id="rejectInformalEmployerModal{{ $verification->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.verifications.informal-employer.reject', $verification) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Verification</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Household: <strong>{{ $verification->employer->company_name }}</strong></p>
                                                            <div class="mb-3">
                                                                <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                                <textarea name="rejection_reason" 
                                                                          class="form-control" 
                                                                          rows="4" 
                                                                          required
                                                                          placeholder="Please provide a clear reason for rejection..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-times me-1"></i>Reject Verification
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Links -->
                <div class="mt-3">
                    {{ $informalEmployerVerifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No informal employer verifications found.</p>
                </div>
            @endif
        </div>

        <!-- Formal Jobseekers Tab -->
        <div class="tab-pane fade" id="formal-jobseeker-tab" role="tabpanel">
            @if($formalJobseekerVerifications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Documents</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($formalJobseekerVerifications as $verification)
                                <tr>
                                    <td>{{ $verification->id }}</td>
                                    <td>
                                        <strong>{{ $verification->jobseekerProfile->user->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $verification->jobseekerProfile->user->email ?? 'N/A' }}</td>
                                    <td>
                                        <small>
                                            @if($verification->government_id_path)
                                                <span class="badge bg-success">✓ ID</span>
                                            @endif
                                            @if($verification->educational_document_path)
                                                <span class="badge bg-success">✓ Edu</span>
                                            @endif
                                            @if($verification->skills_certificate_path)
                                                <span class="badge bg-success">✓ Skills</span>
                                            @endif
                                            @if($verification->nbi_clearance_path)
                                                <span class="badge bg-success">✓ NBI</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($verification->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Approved
                                            </span>
                                        @elseif($verification->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @elseif($verification->status === 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($verification->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $verification->submitted_at ? $verification->submitted_at->format('M d, Y') : 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View User Profile -->
                                            <a href="{{ route('admin.users.show', $verification->jobseekerProfile->user_id) }}" 
                                               class="btn btn-info" 
                                               title="View User Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($verification->status === 'pending')
                                                <!-- Approve Button -->
                                                <form action="{{ route('admin.verifications.formal.approve', $verification) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Approve this verification?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <!-- Reject Button -->
                                                <button type="button" 
                                                        class="btn btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectFormalJobseekerModal{{ $verification->id }}"
                                                        title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif

                                            <!-- View Documents Dropdown -->
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" title="View Documents">
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($verification->government_id_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.formal.download', [$verification, 'government_id']) }}" target="_blank"><i class="fas fa-id-card me-1"></i> Government ID</a></li>
                                                    @endif
                                                    @if($verification->educational_document_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.formal.download', [$verification, 'educational_document']) }}" target="_blank"><i class="fas fa-graduation-cap me-1"></i> Educational Document</a></li>
                                                    @endif
                                                    @if($verification->skills_certificate_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.formal.download', [$verification, 'skills_certificate']) }}" target="_blank"><i class="fas fa-certificate me-1"></i> Skills Certificate</a></li>
                                                    @endif
                                                    @if($verification->nbi_clearance_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.formal.download', [$verification, 'nbi_clearance']) }}" target="_blank"><i class="fas fa-shield-alt me-1"></i> NBI Clearance</a></li>
                                                    @endif
                                                    @if(!$verification->government_id_path && !$verification->educational_document_path && !$verification->skills_certificate_path && !$verification->nbi_clearance_path)
                                                        <li><span class="dropdown-item text-muted">No documents</span></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Reject Modal for Formal Jobseeker -->
                                        <div class="modal fade" id="rejectFormalJobseekerModal{{ $verification->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.verifications.formal.reject', $verification) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Verification</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Jobseeker: <strong>{{ $verification->jobseekerProfile->user->name }}</strong></p>
                                                            <div class="mb-3">
                                                                <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                                <textarea name="rejection_reason" 
                                                                          class="form-control" 
                                                                          rows="4" 
                                                                          required
                                                                          placeholder="Please provide a clear reason for rejection..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-times me-1"></i>Reject Verification
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Links -->
                <div class="mt-3">
                    {{ $formalJobseekerVerifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No formal jobseeker verifications found.</p>
                </div>
            @endif
        </div>

        <!-- Informal Jobseekers Tab -->
        <div class="tab-pane fade" id="informal-jobseeker-tab" role="tabpanel">
            @if($informalJobseekerVerifications->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Documents</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($informalJobseekerVerifications as $verification)
                                <tr>
                                    <td>{{ $verification->id }}</td>
                                    <td>
                                        <strong>{{ $verification->jobseekerProfile->user->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $verification->jobseekerProfile->user->email ?? 'N/A' }}</td>
                                    <td>
                                        <small>
                                            @if($verification->basic_id_path)
                                                <span class="badge bg-success">✓ ID</span>
                                            @endif
                                            @if($verification->barangay_clearance_path)
                                                <span class="badge bg-success">✓ Clearance</span>
                                            @endif
                                            @if($verification->health_certificate_path)
                                                <span class="badge bg-success">✓ Health</span>
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($verification->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Approved
                                            </span>
                                        @elseif($verification->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending
                                            </span>
                                        @elseif($verification->status === 'rejected')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Rejected
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($verification->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $verification->submitted_at ? $verification->submitted_at->format('M d, Y') : 'N/A' }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View User Profile -->
                                            <a href="{{ route('admin.users.show', $verification->jobseekerProfile->user_id) }}" 
                                               class="btn btn-info" 
                                               title="View User Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            @if($verification->status === 'pending')
                                                <!-- Approve Button -->
                                                <form action="{{ route('admin.verifications.informal.approve', $verification) }}" 
                                                      method="POST" 
                                                      class="d-inline"
                                                      onsubmit="return confirm('Approve this verification?')">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>

                                                <!-- Reject Button -->
                                                <button type="button" 
                                                        class="btn btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#rejectInformalJobseekerModal{{ $verification->id }}"
                                                        title="Reject">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif

                                            <!-- View Documents Dropdown -->
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" title="View Documents">
                                                    <i class="fas fa-file-pdf"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($verification->basic_id_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.informal.download', [$verification, 'basic_id']) }}" target="_blank"><i class="fas fa-id-card me-1"></i> Basic ID</a></li>
                                                    @endif
                                                    @if($verification->barangay_clearance_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.informal.download', [$verification, 'barangay_clearance']) }}" target="_blank"><i class="fas fa-file-alt me-1"></i> Barangay Clearance</a></li>
                                                    @endif
                                                    @if($verification->health_certificate_path)
                                                        <li><a class="dropdown-item" href="{{ route('admin.verifications.informal.download', [$verification, 'health_certificate']) }}" target="_blank"><i class="fas fa-heartbeat me-1"></i> Health Certificate</a></li>
                                                    @endif
                                                    @if(!$verification->basic_id_path && !$verification->barangay_clearance_path && !$verification->health_certificate_path)
                                                        <li><span class="dropdown-item text-muted">No documents</span></li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>

                                        <!-- Reject Modal for Informal Jobseeker -->
                                        <div class="modal fade" id="rejectInformalJobseekerModal{{ $verification->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <form action="{{ route('admin.verifications.informal.reject', $verification) }}" method="POST">
                                                        @csrf
                                                        @method('PATCH')
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Reject Verification</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Jobseeker: <strong>{{ $verification->jobseekerProfile->user->name }}</strong></p>
                                                            <div class="mb-3">
                                                                <label class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                                                                <textarea name="rejection_reason" 
                                                                          class="form-control" 
                                                                          rows="4" 
                                                                          required
                                                                          placeholder="Please provide a clear reason for rejection..."></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-times me-1"></i>Reject Verification
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Links -->
                <div class="mt-3">
                    {{ $informalJobseekerVerifications->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No informal jobseeker verifications found.</p>
                </div>
            @endif
        </div>
    </div>
        </div>
    </div>
</div>
@endsection
