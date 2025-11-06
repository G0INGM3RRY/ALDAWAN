@extends('layouts.admin')
@section('title', 'Job Details')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.jobs') }}">Jobs</a></li>
            <li class="breadcrumb-item active">Job Details</li>
        </ol>
    </nav>

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-briefcase me-2"></i>Job Details
        </h1>
        <div>
            <a href="{{ route('admin.jobs') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Jobs
            </a>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Left Column: Job Information -->
        <div class="col-lg-8">
            <!-- Job Basic Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Job Information</h6>
                    <div>
                        @if($job->status === 'open')
                            <span class="badge bg-success"><i class="fas fa-door-open me-1"></i>Open</span>
                        @elseif($job->status === 'closed')
                            <span class="badge bg-danger"><i class="fas fa-door-closed me-1"></i>Closed</span>
                        @elseif($job->status === 'filled')
                            <span class="badge bg-info"><i class="fas fa-check-circle me-1"></i>Filled</span>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">{{ $job->job_title }}</h4>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-building text-primary me-2"></i>Company:</strong><br>
                                {{ $job->user->employer->company_name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-map-marker-alt text-danger me-2"></i>Location:</strong><br>
                                {{ $job->location }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-tag text-info me-2"></i>Job Type:</strong><br>
                                @if($job->job_type === 'formal')
                                    <span class="badge bg-primary">Formal</span>
                                @else
                                    <span class="badge bg-info">Informal</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-clock text-warning me-2"></i>Employment Type:</strong><br>
                                {{ ucfirst(str_replace('-', ' ', $job->employment_type ?? 'N/A')) }}
                            </p>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-money-bill text-success me-2"></i>Salary:</strong><br>
                                @if($job->salary)
                                    ₱{{ number_format($job->salary, 2) }}
                                @else
                                    <span class="text-muted">Not specified</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong><i class="fas fa-users text-secondary me-2"></i>Positions Available:</strong><br>
                                {{ $job->positions_available ?? 'N/A' }}
                            </p>
                        </div>
                    </div>

                    @if($job->remote_work_available)
                        <div class="alert alert-info">
                            <i class="fas fa-home me-2"></i>Remote work available
                        </div>
                    @endif

                    <hr>

                    <h5 class="mb-3">Description</h5>
                    <div class="job-description">
                        {!! nl2br(e($job->description)) !!}
                    </div>

                    @if($job->requirements)
                        <hr>
                        <h5 class="mb-3">Requirements</h5>
                        <div class="job-requirements">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    @endif

                    @if($job->benefits)
                        <hr>
                        <h5 class="mb-3">Benefits</h5>
                        <div class="job-benefits">
                            {!! nl2br(e($job->benefits)) !!}
                        </div>
                    @endif

                    @if($job->minimumEducationLevel)
                        <hr>
                        <p class="mb-2">
                            <strong><i class="fas fa-graduation-cap text-primary me-2"></i>Minimum Education:</strong>
                            {{ $job->minimumEducationLevel->level_name ?? 'N/A' }}
                        </p>
                    @endif

                    @if($job->minimum_experience_years)
                        <p class="mb-2">
                            <strong><i class="fas fa-briefcase text-info me-2"></i>Experience Required:</strong>
                            {{ $job->minimum_experience_years }} year(s)
                        </p>
                    @endif

                    @if($job->requiredSkills && $job->requiredSkills->count() > 0)
                        <hr>
                        <h5 class="mb-3">Required Skills</h5>
                        <div>
                            @foreach($job->requiredSkills as $skill)
                                <span class="badge bg-secondary me-1 mb-1">{{ $skill->skill_name }}</span>
                            @endforeach
                        </div>
                    @endif

                    @if($job->accessibility_notes)
                        <hr>
                        <div class="alert alert-warning">
                            <strong><i class="fas fa-wheelchair me-2"></i>Accessibility Notes:</strong><br>
                            {{ $job->accessibility_notes }}
                        </div>
                    @endif
                </div>
                <div class="card-footer">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>Posted: {{ $job->created_at->format('F d, Y h:i A') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Right Column: Actions & Stats -->
        <div class="col-lg-4">
            <!-- Quick Actions Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <!-- Change Status -->
                        <div class="dropdown">
                            <button class="btn btn-warning dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-exchange-alt me-1"></i>Change Status
                            </button>
                            <ul class="dropdown-menu w-100">
                                @if($job->status !== 'open')
                                    <li>
                                        <form action="{{ route('admin.jobs.updateStatus', $job) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="open">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-door-open text-success me-1"></i> Set as Open
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                @if($job->status !== 'closed')
                                    <li>
                                        <form action="{{ route('admin.jobs.updateStatus', $job) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="closed">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-door-closed text-danger me-1"></i> Set as Closed
                                            </button>
                                        </form>
                                    </li>
                                @endif
                                @if($job->status !== 'filled')
                                    <li>
                                        <form action="{{ route('admin.jobs.updateStatus', $job) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="filled">
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-check-circle text-info me-1"></i> Mark as Filled
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            </ul>
                        </div>

                        <!-- Delete Job -->
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteJobModal">
                            <i class="fas fa-trash me-1"></i>Delete Job
                        </button>
                    </div>
                </div>
            </div>

            <!-- Statistics Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h4 class="mb-0">{{ $job->applications->count() }}</h4>
                        <small class="text-muted">Total Applications</small>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <h4 class="mb-0">{{ $job->applications->where('status', 'pending')->count() }}</h4>
                        <small class="text-muted">Pending Applications</small>
                    </div>
                    <hr>
                    <div>
                        <h4 class="mb-0">{{ $job->applications->where('status', 'accepted')->count() }}</h4>
                        <small class="text-muted">Accepted Applications</small>
                    </div>
                </div>
            </div>

            <!-- Employer Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Employer Information</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Company:</strong><br>
                        {{ $job->user->employer->company_name ?? 'N/A' }}
                    </p>
                    <p class="mb-2">
                        <strong>Contact Person:</strong><br>
                        {{ $job->user->name ?? 'N/A' }}
                    </p>
                    <p class="mb-2">
                        <strong>Email:</strong><br>
                        <a href="mailto:{{ $job->user->email }}">{{ $job->user->email ?? 'N/A' }}</a>
                    </p>
                    <hr>
                    <a href="{{ route('admin.users.show', $job->user->id) }}" class="btn btn-sm btn-primary w-100">
                        <i class="fas fa-user me-1"></i>View Employer Profile
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Section -->
    @if($job->applications->count() > 0)
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-users me-2"></i>Applications ({{ $job->applications->count() }})
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Applicant</th>
                                <th>Email</th>
                                <th>Applied Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($job->applications as $application)
                                <tr>
                                    <td>
                                        <strong>{{ $application->jobseekerProfile->user->name ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ $application->jobseekerProfile->user->email ?? 'N/A' }}</td>
                                    <td>
                                        <small>{{ $application->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        @if($application->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @elseif($application->status === 'accepted')
                                            <span class="badge bg-success">Accepted</span>
                                        @elseif($application->status === 'rejected')
                                            <span class="badge bg-danger">Rejected</span>
                                        @else
                                            <span class="badge bg-secondary">{{ ucfirst($application->status) }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.users.show', $application->jobseekerProfile->user_id) }}" 
                                           class="btn btn-sm btn-info"
                                           title="View Applicant">
                                            <i class="fas fa-eye"></i> View Profile
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteJobModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this job?</p>
                <p><strong>{{ $job->job_title }}</strong></p>
                <p class="text-danger">
                    <i class="fas fa-exclamation-triangle me-1"></i>
                    This action cannot be undone. All {{ $job->applications->count() }} application(s) will also be affected.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.jobs.destroy', $job) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Delete Job
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
