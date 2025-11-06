@extends('layouts.admin')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header with Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>User Profile Details</h2>
                <div class="btn-group">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    
                    <!-- Archive/Restore Button -->
                    @if($user->deleted_at)
                        <form action="{{ route('admin.users.restore', $user->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success" title="Restore User">
                                <i class="fas fa-undo"></i> Restore
                            </button>
                        </form>
                    @else
                        <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#archiveModal" title="Archive User">
                            <i class="fas fa-archive"></i> Archive
                        </button>
                    @endif
                    
                    <!-- Delete Button -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" title="Permanently Delete User">
                        <i class="fas fa-trash-alt"></i> Delete
                    </button>
                    
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back
                    </a>
                </div>
            </div>

            <!-- Basic Information Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">User ID:</label>
                            <p class="mb-0">{{ $user->id }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Full Name:</label>
                            <p class="mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Email:</label>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Role:</label>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Status Card -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Account Status</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Email Verified:</label>
                            <p class="mb-0">
                                @if($user->email_verified_at)
                                    <span class="badge bg-success">Yes</span>
                                    <small class="text-muted d-block mt-1">
                                        Verified on {{ $user->email_verified_at->format('M d, Y H:i A') }}
                                    </small>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Account Status:</label>
                            <p class="mb-0">
                                @if($user->account_status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($user->account_status === 'inactive')
                                    <span class="badge bg-warning">Inactive</span>
                                @elseif($user->account_status === 'suspended')
                                    <span class="badge bg-danger">Suspended</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($user->account_status) }}</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Account Created:</label>
                            <p class="mb-0">{{ $user->created_at->format('M d, Y H:i A') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold">Last Updated:</label>
                            <p class="mb-0">{{ $user->updated_at->format('M d, Y H:i A') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role-Specific Information -->
            @if($user->role === 'employer' && $user->employer)
                <!-- Employer Profile Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Employer Profile</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Employer Type:</label>
                                <p class="mb-0">
                                    <span class="badge bg-info">{{ ucfirst($user->employer->employer_type) }}</span>
                                </p>
                            </div>
                            @if($user->employer->employer_type === 'formal')
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Company Name:</label>
                                    <p class="mb-0">{{ $user->employer->company_name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Company Type:</label>
                                    <p class="mb-0">{{ $user->employer->companyType->type ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Registration Number:</label>
                                    <p class="mb-0">{{ $user->employer->registration_number ?? 'N/A' }}</p>
                                </div>
                            @else
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Household Name:</label>
                                    <p class="mb-0">{{ $user->employer->household_name ?? 'N/A' }}</p>
                                </div>
                            @endif
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Contact Number:</label>
                                <p class="mb-0">{{ $user->employer->contact_number ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="fw-bold">Address:</label>
                                <p class="mb-0">{{ $user->employer->address ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Company Verification Documents Card -->
                @if($user->employer->employer_type === 'formal')
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-shield-check"></i> Verification Documents
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($user->employer->verification)
                                <!-- Verification Status -->
                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <label class="fw-bold text-primary">Verification Status:</label>
                                        <p class="mb-0">
                                            @if($user->employer->verification->status === 'approved')
                                                <span class="badge bg-success fs-6">✓ Approved</span>
                                            @elseif($user->employer->verification->status === 'pending')
                                                <span class="badge bg-warning fs-6">⏱ Pending Review</span>
                                            @elseif($user->employer->verification->status === 'rejected')
                                                <span class="badge bg-danger fs-6">✗ Rejected</span>
                                            @else
                                                <span class="badge bg-secondary fs-6">{{ ucfirst($user->employer->verification->status) }}</span>
                                            @endif
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="fw-bold text-primary">Submitted At:</label>
                                        <p class="mb-0">
                                            {{ $user->employer->verification->submitted_at ? $user->employer->verification->submitted_at->format('M d, Y \a\t h:i A') : 'Not submitted' }}
                                        </p>
                                    </div>
                                </div>

                                <hr>

                                <!-- Business Information -->
                                <h6 class="text-primary mb-3"><i class="bi bi-building"></i> Business Information</h6>
                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold">Business Registration Number:</label>
                                        <p class="mb-0">{{ $user->employer->verification->business_registration_number ?? 'Not provided' }}</p>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="fw-bold">Tax ID:</label>
                                        <p class="mb-0">{{ $user->employer->verification->tax_id ?? 'Not provided' }}</p>
                                    </div>
                                </div>

                                <hr>

                                <!-- Submitted Documents -->
                                <h6 class="text-primary mb-3"><i class="bi bi-file-earmark-text"></i> Submitted Documents</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Document Type</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><strong>Verification Document</strong> (Business Registration/License)</td>
                                                <td>
                                                    @if($user->employer->verification->verification_document_path)
                                                        <span class="badge bg-success">Submitted</span>
                                                    @else
                                                        <span class="badge bg-danger">Not Submitted</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($user->employer->verification->verification_document_path)
                                                        <a href="{{ Storage::url($user->employer->verification->verification_document_path) }}" 
                                                           target="_blank" 
                                                           class="btn btn-sm btn-primary">
                                                            <i class="bi bi-eye"></i> View Document
                                                        </a>
                                                    @else
                                                        <span class="text-muted">No document uploaded</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Additional Information -->
                                @if($user->employer->verification->verification_notes || $user->employer->verification->rejection_reason || $user->employer->verification->verified_at)
                                    <hr>
                                    <h6 class="text-primary mb-3"><i class="bi bi-info-circle"></i> Additional Information</h6>
                                @endif

                                @if($user->employer->verification->verification_notes)
                                    <div class="alert alert-info">
                                        <strong>Verification Notes:</strong><br>
                                        {{ $user->employer->verification->verification_notes }}
                                    </div>
                                @endif

                                @if($user->employer->verification->rejection_reason)
                                    <div class="alert alert-danger">
                                        <strong><i class="bi bi-exclamation-triangle"></i> Rejection Reason:</strong><br>
                                        {{ $user->employer->verification->rejection_reason }}
                                    </div>
                                @endif

                                @if($user->employer->verification->verified_at)
                                    <div class="alert alert-success">
                                        <strong><i class="bi bi-check-circle"></i> Verified:</strong><br>
                                        {{ $user->employer->verification->verified_at->format('M d, Y \a\t h:i A') }}
                                        @if($user->employer->verification->verifier)
                                            <br><small>Verified by: {{ $user->employer->verification->verifier->name }}</small>
                                        @endif
                                    </div>
                                @endif
                            @else
                                <div class="bg-warning bg-opacity-10 border border-warning rounded p-4">
                                    <h5 class="text-warning"><i class="bi bi-exclamation-triangle"></i> No Verification Documents Submitted</h5>
                                    <p class="mb-3">This formal employer has not submitted their verification documents yet.</p>
                                    <hr>
                                    <p class="mb-2"><strong>Required Documents:</strong></p>
                                    <ul class="mb-0">
                                        <li>Business Registration Certificate or License</li>
                                        <li>Business Registration Number</li>
                                        <li>Tax Identification Number (TIN)</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @elseif($user->employer->employer_type === 'informal')
                    <div class="card mb-4 border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="bi bi-shield-check"></i> Verification Documents
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            @if($user->employer->informalVerification)
                            <!-- Verification Status -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="fw-bold text-info">Verification Status:</label>
                                    <p class="mb-0">
                                        @if($user->employer->informalVerification->status === 'approved')
                                            <span class="badge bg-success fs-6">✓ Approved</span>
                                        @elseif($user->employer->informalVerification->status === 'pending')
                                            <span class="badge bg-warning fs-6">⏱ Pending Review</span>
                                        @elseif($user->employer->informalVerification->status === 'rejected')
                                            <span class="badge bg-danger fs-6">✗ Rejected</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">{{ ucfirst($user->employer->informalVerification->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold text-info">Submitted At:</label>
                                    <p class="mb-0">
                                        {{ $user->employer->informalVerification->submitted_at ? $user->employer->informalVerification->submitted_at->format('M d, Y \a\t h:i A') : 'Not submitted' }}
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <!-- Submitted Documents -->
                            <h6 class="text-info mb-3"><i class="bi bi-file-earmark-text"></i> Submitted Documents</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Valid ID</strong> (National ID, Driver's License, etc.)</td>
                                            <td>
                                                @if($user->employer->informalVerification->valid_id_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-danger">Not Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->employer->informalVerification->valid_id_path)
                                                    <a href="{{ Storage::url($user->employer->informalVerification->valid_id_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">No document uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Proof of Address</strong> (Utility Bill, Barangay Certificate)</td>
                                            <td>
                                                @if($user->employer->informalVerification->proof_of_address_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-danger">Not Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->employer->informalVerification->proof_of_address_path)
                                                    <a href="{{ Storage::url($user->employer->informalVerification->proof_of_address_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">No document uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Barangay Clearance</strong> (Optional)</td>
                                            <td>
                                                @if($user->employer->informalVerification->barangay_clearance_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-secondary">Optional</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->employer->informalVerification->barangay_clearance_path)
                                                    <a href="{{ Storage::url($user->employer->informalVerification->barangay_clearance_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">Not uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Additional Information -->
                            @if($user->employer->informalVerification->verification_notes || $user->employer->informalVerification->rejection_reason || $user->employer->informalVerification->verified_at)
                                <hr>
                                <h6 class="text-info mb-3"><i class="bi bi-info-circle"></i> Additional Information</h6>
                            @endif

                            @if($user->employer->informalVerification->verification_notes)
                                <div class="bg-info bg-opacity-10 border border-info rounded p-3 mb-3">
                                    <strong>Verification Notes:</strong><br>
                                    {{ $user->employer->informalVerification->verification_notes }}
                                </div>
                            @endif

                            @if($user->employer->informalVerification->rejection_reason)
                                <div class="bg-danger bg-opacity-10 border border-danger rounded p-3 mb-3">
                                    <strong><i class="bi bi-exclamation-triangle"></i> Rejection Reason:</strong><br>
                                    {{ $user->employer->informalVerification->rejection_reason }}
                                </div>
                            @endif

                            @if($user->employer->informalVerification->verified_at)
                                <div class="bg-success bg-opacity-10 border border-success rounded p-3">
                                    <strong><i class="bi bi-check-circle"></i> Verified:</strong><br>
                                    {{ $user->employer->informalVerification->verified_at->format('M d, Y \a\t h:i A') }}
                                    @if($user->employer->informalVerification->verifier)
                                        <br><small>Verified by: {{ $user->employer->informalVerification->verifier->name }}</small>
                                    @endif
                                </div>
                            @endif
                            @else
                                <div class="bg-warning bg-opacity-10 border border-warning rounded p-4">
                                    <h5 class="text-warning"><i class="bi bi-exclamation-triangle"></i> No Verification Documents Submitted</h5>
                                    <p class="mb-3">This informal employer (household) has not submitted their verification documents yet.</p>
                                    <hr>
                                    <p class="mb-2"><strong>Required Documents:</strong></p>
                                    <ul class="mb-0">
                                        <li>Valid ID (National ID, Driver's License, Passport, PhilHealth ID, etc.)</li>
                                        <li>Proof of Address (Utility Bill, Barangay Certificate, Lease Contract)</li>
                                        <li>Barangay Clearance (Optional but recommended)</li>
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif

            @if($user->role === 'jobseeker')
                <!-- Job Seeker Profile Card -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Job Seeker Profile</h5>
                    </div>
                    <div class="card-body">
                        @if($user->jobseekerProfile)
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Job Seeker Type:</label>
                                <p class="mb-0">
                                    <span class="badge bg-info">{{ ucfirst($user->jobseekerProfile->job_seeker_type) }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">First Name:</label>
                                <p class="mb-0">{{ $user->jobseekerProfile->first_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Middle Name:</label>
                                <p class="mb-0">{{ $user->jobseekerProfile->middle_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Last Name:</label>
                                <p class="mb-0">{{ $user->jobseekerProfile->last_name ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Date of Birth:</label>
                                <p class="mb-0">{{ $user->jobseekerProfile->dob ? \Carbon\Carbon::parse($user->jobseekerProfile->dob)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Gender:</label>
                                <p class="mb-0">{{ ucfirst($user->jobseekerProfile->gender) ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">Phone Number:</label>
                                <p class="mb-0">{{ $user->jobseekerProfile->phone_number ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="fw-bold">National ID:</label>
                                <p class="mb-0">{{ $user->jobseekerProfile->national_id ?? 'N/A' }}</p>
                            </div>
                            @if($user->jobseekerProfile->job_seeker_type === 'formal')
                                <div class="col-md-6 mb-3">
                                    <label class="fw-bold">Highest Education Level:</label>
                                    <p class="mb-0">{{ $user->jobseekerProfile->highest_education_level ?? 'N/A' }}</p>
                                </div>
                            @endif
                        </div>
                        @else
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> <strong>Profile Not Completed</strong>
                            <p class="mb-0 mt-2">This job seeker has not completed their profile yet.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Verification Documents Section -->
                @if($user->jobseekerProfile)
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="bi bi-shield-check"></i> Verification Documents
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->jobseekerProfile->job_seeker_type === 'formal')
                            @if($user->jobseekerProfile->formalVerification)
                            <!-- Verification Status -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="fw-bold text-primary">Verification Status:</label>
                                    <p class="mb-0">
                                        @if($user->jobseekerProfile->formalVerification->status === 'approved')
                                            <span class="badge bg-success fs-6">✓ Approved</span>
                                        @elseif($user->jobseekerProfile->formalVerification->status === 'pending')
                                            <span class="badge bg-warning fs-6">⏱ Pending Review</span>
                                        @elseif($user->jobseekerProfile->formalVerification->status === 'rejected')
                                            <span class="badge bg-danger fs-6">✗ Rejected</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">{{ ucfirst($user->jobseekerProfile->formalVerification->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold text-primary">Submitted At:</label>
                                    <p class="mb-0">
                                        {{ $user->jobseekerProfile->formalVerification->submitted_at ? $user->jobseekerProfile->formalVerification->submitted_at->format('M d, Y \a\t h:i A') : 'Not submitted' }}
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <!-- Submitted Documents -->
                            <h6 class="text-primary mb-3"><i class="bi bi-file-earmark-text"></i> Submitted Documents</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Government ID</strong></td>
                                            <td>
                                                @if($user->jobseekerProfile->formalVerification->government_id_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-danger">Not Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->jobseekerProfile->formalVerification->government_id_path)
                                                    <a href="{{ Storage::url($user->jobseekerProfile->formalVerification->government_id_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">No document uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Educational Document</strong></td>
                                            <td>
                                                @if($user->jobseekerProfile->formalVerification->educational_document_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-danger">Not Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->jobseekerProfile->formalVerification->educational_document_path)
                                                    <a href="{{ Storage::url($user->jobseekerProfile->formalVerification->educational_document_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">No document uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Skills Certificate</strong></td>
                                            <td>
                                                @if($user->jobseekerProfile->formalVerification->skills_certificate_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-danger">Not Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->jobseekerProfile->formalVerification->skills_certificate_path)
                                                    <a href="{{ Storage::url($user->jobseekerProfile->formalVerification->skills_certificate_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">No document uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>NBI Clearance</strong></td>
                                            <td>
                                                @if($user->jobseekerProfile->formalVerification->nbi_clearance_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-danger">Not Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->jobseekerProfile->formalVerification->nbi_clearance_path)
                                                    <a href="{{ Storage::url($user->jobseekerProfile->formalVerification->nbi_clearance_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">No document uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Additional Information -->
                            @if($user->jobseekerProfile->formalVerification->verification_notes || $user->jobseekerProfile->formalVerification->rejection_reason || $user->jobseekerProfile->formalVerification->verified_at)
                                <hr>
                                <h6 class="text-primary mb-3"><i class="bi bi-info-circle"></i> Additional Information</h6>
                            @endif

                            @if($user->jobseekerProfile->formalVerification->verification_notes)
                                <div class="alert alert-info">
                                    <strong>Verification Notes:</strong><br>
                                    {{ $user->jobseekerProfile->formalVerification->verification_notes }}
                                </div>
                            @endif

                            @if($user->jobseekerProfile->formalVerification->rejection_reason)
                                <div class="alert alert-danger">
                                    <strong><i class="bi bi-exclamation-triangle"></i> Rejection Reason:</strong><br>
                                    {{ $user->jobseekerProfile->formalVerification->rejection_reason }}
                                </div>
                            @endif

                            @if($user->jobseekerProfile->formalVerification->verified_at)
                                <div class="alert alert-success">
                                    <strong><i class="bi bi-check-circle"></i> Verified:</strong><br>
                                    {{ $user->jobseekerProfile->formalVerification->verified_at->format('M d, Y \a\t h:i A') }}
                                    @if($user->jobseekerProfile->formalVerification->verifier)
                                        <br><small>Verified by: {{ $user->jobseekerProfile->formalVerification->verifier->name }}</small>
                                    @endif
                                </div>
                            @endif
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="fw-bold">Verification Status:</label>
                                    <p class="mb-0">
                                        @if($user->jobseekerProfile->formalVerification->status === 'approved')
                                            <span class="badge bg-success">Approved</span>
                                        @elseif($user->jobseekerProfile->formalVerification->status === 'pending')
                                            <span class="badge bg-warning">Pending Review</span>
                                        @elseif($user->jobseekerProfile->formalVerification->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($user->jobseekerProfile->formalVerification->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold">Submitted At:</label>
                                    <p class="mb-0">
                                        {{ $user->jobseekerProfile->formalVerification->submitted_at ? $user->jobseekerProfile->formalVerification->submitted_at->format('M d, Y H:i A') : 'N/A' }}
                                    </p>
                                </div>
                            </div>

                            @else
                                <div class="bg-warning bg-opacity-10 border border-warning rounded p-4">
                                    <h5 class="text-warning"><i class="bi bi-exclamation-triangle"></i> No Verification Documents Submitted</h5>
                                    <p class="mb-3">This formal job seeker has not submitted their verification documents yet.</p>
                                    <hr>
                                    <p class="mb-2"><strong>Required Documents:</strong></p>
                                    <ul class="mb-0">
                                        <li>Government-issued ID (National ID, Passport, Driver's License)</li>
                                        <li>Educational Documents (Diploma, Transcript of Records)</li>
                                        <li>Skills Certificate or Training Certificates</li>
                                        <li>NBI Clearance or Police Clearance</li>
                                    </ul>
                                </div>
                            @endif
                        @elseif($user->jobseekerProfile->job_seeker_type === 'informal')
                            @if($user->jobseekerProfile->informalVerification)
                            <!-- Verification Status -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="fw-bold text-primary">Verification Status:</label>
                                    <p class="mb-0">
                                        @if($user->jobseekerProfile->informalVerification->status === 'approved')
                                            <span class="badge bg-success fs-6">✓ Approved</span>
                                        @elseif($user->jobseekerProfile->informalVerification->status === 'pending')
                                            <span class="badge bg-warning fs-6">⏱ Pending Review</span>
                                        @elseif($user->jobseekerProfile->informalVerification->status === 'rejected')
                                            <span class="badge bg-danger fs-6">✗ Rejected</span>
                                        @else
                                            <span class="badge bg-secondary fs-6">{{ ucfirst($user->jobseekerProfile->informalVerification->status) }}</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <label class="fw-bold text-primary">Submitted At:</label>
                                    <p class="mb-0">
                                        {{ $user->jobseekerProfile->informalVerification->submitted_at ? $user->jobseekerProfile->informalVerification->submitted_at->format('M d, Y \a\t h:i A') : 'Not submitted' }}
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <!-- Submitted Documents -->
                            <h6 class="text-primary mb-3"><i class="bi bi-file-earmark-text"></i> Submitted Documents</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Document Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><strong>Basic ID</strong></td>
                                            <td>
                                                @if($user->jobseekerProfile->informalVerification->basic_id_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-danger">Not Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->jobseekerProfile->informalVerification->basic_id_path)
                                                    <a href="{{ Storage::url($user->jobseekerProfile->informalVerification->basic_id_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">No document uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Barangay Clearance</strong></td>
                                            <td>
                                                @if($user->jobseekerProfile->informalVerification->barangay_clearance_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-danger">Not Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->jobseekerProfile->informalVerification->barangay_clearance_path)
                                                    <a href="{{ Storage::url($user->jobseekerProfile->informalVerification->barangay_clearance_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">No document uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><strong>Health Certificate</strong></td>
                                            <td>
                                                @if($user->jobseekerProfile->informalVerification->health_certificate_path)
                                                    <span class="badge bg-success">Submitted</span>
                                                @else
                                                    <span class="badge bg-danger">Not Submitted</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($user->jobseekerProfile->informalVerification->health_certificate_path)
                                                    <a href="{{ Storage::url($user->jobseekerProfile->informalVerification->health_certificate_path) }}" 
                                                       target="_blank" 
                                                       class="btn btn-sm btn-primary">
                                                        <i class="bi bi-eye"></i> View Document
                                                    </a>
                                                @else
                                                    <span class="text-muted">No document uploaded</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Additional Information -->
                            @if($user->jobseekerProfile->informalVerification->verification_notes || $user->jobseekerProfile->informalVerification->rejection_reason || $user->jobseekerProfile->informalVerification->verified_at)
                                <hr>
                                <h6 class="text-primary mb-3"><i class="bi bi-info-circle"></i> Additional Information</h6>
                            @endif

                            @if($user->jobseekerProfile->informalVerification->verification_notes)
                                <div class="alert alert-info">
                                    <strong>Verification Notes:</strong><br>
                                    {{ $user->jobseekerProfile->informalVerification->verification_notes }}
                                </div>
                            @endif

                            @if($user->jobseekerProfile->informalVerification->rejection_reason)
                                <div class="alert alert-danger">
                                    <strong><i class="bi bi-exclamation-triangle"></i> Rejection Reason:</strong><br>
                                    {{ $user->jobseekerProfile->informalVerification->rejection_reason }}
                                </div>
                            @endif

                            @if($user->jobseekerProfile->informalVerification->verified_at)
                                <div class="alert alert-success">
                                    <strong><i class="bi bi-check-circle"></i> Verified:</strong><br>
                                    {{ $user->jobseekerProfile->informalVerification->verified_at->format('M d, Y \a\t h:i A') }}
                                    @if($user->jobseekerProfile->informalVerification->verifier)
                                        <br><small>Verified by: {{ $user->jobseekerProfile->informalVerification->verifier->name }}</small>
                                    @endif
                                </div>
                            @endif
                            @else
                                <div class="bg-warning bg-opacity-10 border border-warning rounded p-4">
                                    <h5 class="text-warning"><i class="bi bi-exclamation-triangle"></i> No Verification Documents Submitted</h5>
                                    <p class="mb-3">This informal job seeker has not submitted their verification documents yet.</p>
                                    <hr>
                                    <p class="mb-2"><strong>Required Documents:</strong></p>
                                    <ul class="mb-0">
                                        <li>Basic ID (National ID, Voter's ID, PhilHealth ID, etc.)</li>
                                        <li>Barangay Clearance</li>
                                        <li>Health Certificate or Medical Clearance</li>
                                    </ul>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                @endif
            @endif

        </div>
    </div>
</div>

<!-- Archive Confirmation Modal -->
<div class="modal fade" id="archiveModal" tabindex="-1" aria-labelledby="archiveModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title" id="archiveModalLabel">
                    <i class="fas fa-archive"></i> Archive User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to archive <strong>{{ $user->name }}</strong>?</p>
                <p class="text-muted">Archived users:</p>
                <ul class="text-muted">
                    <li>Cannot log in to the system</li>
                    <li>Will not appear in active user lists</li>
                    <li>Can be restored later if needed</li>
                    <li>Data is preserved (soft delete)</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-archive"></i> Archive User
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Permanently Delete User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-danger"><strong>⚠️ Warning: This action cannot be undone!</strong></p>
                <p>Are you sure you want to <strong>permanently delete</strong> {{ $user->name }}?</p>
                <p class="text-muted">This will:</p>
                <ul class="text-muted">
                    <li>Permanently remove all user data</li>
                    <li>Delete all associated records</li>
                    <li>Cannot be restored</li>
                </ul>
                <p class="text-info"><em>💡 Tip: Consider using "Archive" instead to preserve data.</em></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="force_delete" value="1">
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt"></i> Yes, Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
