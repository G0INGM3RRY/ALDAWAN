@extends('layouts.admin')
@section('title', 'Job Management')
@section('page-title', 'Job Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-briefcase me-2"></i>Job Management
        </h1>
        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i>Create Job Posting
        </a>
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
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Jobs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_jobs'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-briefcase fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Open Jobs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['open_jobs'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Closed Jobs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['closed_jobs'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-closed fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Applications</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $stats['total_applications'] }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.jobs') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label">
                        <i class="fas fa-search me-1"></i>Search
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           placeholder="Search job title, company..." 
                           value="{{ request('search') }}">
                    <small class="text-muted">Search in titles and companies</small>
                </div>
                
                <div class="col-md-2">
                    <label for="status" class="form-label">
                        <i class="fas fa-info-circle me-1"></i>Status
                    </label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>
                            Open
                        </option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>
                            Closed
                        </option>
                        <option value="filled" {{ request('status') == 'filled' ? 'selected' : '' }}>
                            Filled
                        </option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="job_type" class="form-label">
                        <i class="fas fa-tag me-1"></i>Job Type
                    </label>
                    <select class="form-select" id="job_type" name="job_type">
                        <option value="">All Types</option>
                        <option value="formal" {{ request('job_type') == 'formal' ? 'selected' : '' }}>
                            Formal
                        </option>
                        <option value="informal" {{ request('job_type') == 'informal' ? 'selected' : '' }}>
                            Informal
                        </option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="employment_type" class="form-label">
                        <i class="fas fa-clock me-1"></i>Employment
                    </label>
                    <select class="form-select" id="employment_type" name="employment_type">
                        <option value="">All Types</option>
                        <option value="full-time" {{ request('employment_type') == 'full-time' ? 'selected' : '' }}>
                            Full-time
                        </option>
                        <option value="part-time" {{ request('employment_type') == 'part-time' ? 'selected' : '' }}>
                            Part-time
                        </option>
                        <option value="contract" {{ request('employment_type') == 'contract' ? 'selected' : '' }}>
                            Contract
                        </option>
                        <option value="temporary" {{ request('employment_type') == 'temporary' ? 'selected' : '' }}>
                            Temporary
                        </option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.jobs') }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Jobs Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>All Jobs ({{ $jobs->total() }})
            </h6>
        </div>
        <div class="card-body">
            @if($jobs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Job Title</th>
                                <th>Company</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Employment</th>
                                <th>Salary</th>
                                <th>Status</th>
                                <th>Applications</th>
                                <th>Posted</th>
                                <th>Actions</th>
                            </tr>  
                        </thead>
                        <tbody>
                            @foreach($jobs as $job)
                            <tr>
                                <td>{{ $job->id }}</td>
                                <td>
                                    <strong>{{ $job->job_title }}</strong>
                                    @if($job->remote_work_available)
                                        <br><small class="text-info"><i class="fas fa-home"></i> Remote</small>
                                    @endif
                                </td>
                                <td>
                                    {{ $job->user->employer->company_name ?? 'N/A' }}
                                    <br>
                                    <small class="text-muted">{{ $job->user->email ?? '' }}</small>
                                </td>
                                <td>
                                    <small>{{ $job->location }}</small>
                                </td>
                                <td>
                                    @if($job->job_type === 'formal')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-building me-1"></i>Formal
                                        </span>
                                    @elseif($job->job_type === 'informal')
                                        <span class="badge bg-info">
                                            <i class="fas fa-handshake me-1"></i>Informal
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($job->job_type) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ ucfirst(str_replace('-', ' ', $job->employment_type ?? 'N/A')) }}</small>
                                </td>
                                <td>
                                    @if($job->salary)
                                        <small>₱{{ number_format($job->salary, 2) }}</small>
                                    @else
                                        <small class="text-muted">Not specified</small>
                                    @endif
                                </td>
                                <td>
                                    @if($job->status === 'open')
                                        <span class="badge bg-success">
                                            <i class="fas fa-door-open me-1"></i>Open
                                        </span>
                                    @elseif($job->status === 'closed')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-door-closed me-1"></i>Closed
                                        </span>
                                    @elseif($job->status === 'filled')
                                        <span class="badge bg-info">
                                            <i class="fas fa-check-circle me-1"></i>Filled
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($job->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $job->applications->count() }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $job->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <!-- View Job Button -->
                                        <a href="{{ route('admin.jobs.show', $job->id) }}" 
                                           class="btn btn-info"
                                           title="View Job Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <!-- Edit Job Button -->
                                        <a href="{{ route('admin.jobs.edit', $job->id) }}" 
                                           class="btn btn-primary"
                                           title="Edit Job">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Change Status Dropdown -->
                                        <div class="btn-group" role="group">
                                            <button type="button" 
                                                    class="btn btn-warning dropdown-toggle" 
                                                    data-bs-toggle="dropdown" 
                                                    title="Change Status">
                                                <i class="fas fa-exchange-alt"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if($job->status !== 'open')
                                                    <li>
                                                        <form action="{{ route('admin.jobs.updateStatus', $job) }}" 
                                                              method="POST" 
                                                              class="d-inline">
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
                                                        <form action="{{ route('admin.jobs.updateStatus', $job) }}" 
                                                              method="POST" 
                                                              class="d-inline">
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
                                                        <form action="{{ route('admin.jobs.updateStatus', $job) }}" 
                                                              method="POST" 
                                                              class="d-inline">
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
                                        
                                        <!-- Delete Job Button -->
                                        <button type="button" 
                                                class="btn btn-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteJobModal{{ $job->id }}"
                                                title="Delete Job">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Delete Confirmation Modal -->
                                    <div class="modal fade" id="deleteJobModal{{ $job->id }}" tabindex="-1">
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
                                                    <form action="{{ route('admin.jobs.destroy', $job) }}" 
                                                          method="POST" 
                                                          class="d-inline">
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
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-3">
                    {{ $jobs->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No jobs found.</p>
                    @if(request('search') || request('status') || request('job_type') || request('employment_type'))
                        <a href="{{ route('admin.jobs') }}" class="btn btn-primary">
                            <i class="fas fa-redo me-1"></i>Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
