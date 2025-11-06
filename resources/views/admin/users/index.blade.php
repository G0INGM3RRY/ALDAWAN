@extends('layouts.admin')
@section('title', 'User Management')
@section('page-title', 'User Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users me-2"></i>User Management
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

    <!-- Search and Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">
                        <i class="fas fa-search me-1"></i>Search
                    </label>
                    <input type="text" 
                           class="form-control" 
                           id="search" 
                           name="search" 
                           placeholder="Search by name or email..." 
                           value="{{ request('search') }}">
                    <small class="text-muted">Search in names and emails</small>
                </div>
                
                <div class="col-md-3">
                    <label for="role" class="form-label">
                        <i class="fas fa-user-tag me-1"></i>Role
                    </label>
                    <select class="form-select" id="role" name="role">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>
                        <option value="employer" {{ request('role') == 'employer' ? 'selected' : '' }}>
                            Employer
                        </option>
                        <option value="seeker" {{ request('role') == 'seeker' ? 'selected' : '' }}>
                            Job Seeker
                        </option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="status" class="form-label">
                        <i class="fas fa-toggle-on me-1"></i>Status
                    </label>
                    <select class="form-select" id="status" name="status">
                        <option value="active" {{ request('status', 'active') == 'active' ? 'selected' : '' }}>
                            Active Only
                        </option>
                        <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>
                            Archived Only
                        </option>
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>
                            All Users
                        </option>
                    </select>
                </div>
                
                <div class="col-md-2">
                    <label for="verified" class="form-label">
                        <i class="fas fa-check-circle me-1"></i>Verified
                    </label>
                    <select class="form-select" id="verified" name="verified">
                        <option value="">All</option>
                        <option value="yes" {{ request('verified') == 'yes' ? 'selected' : '' }}>
                            Verified
                        </option>
                        <option value="no" {{ request('verified') == 'no' ? 'selected' : '' }}>
                            Not Verified
                        </option>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label d-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search me-1"></i>Apply Filters
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-redo me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>All Users ({{ $users->total() }})
            </h6>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Email Verified</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>  
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="{{ $user->deleted_at ? 'table-secondary' : '' }}">
                                <td>{{ $user->id }}</td>
                                <td>
                                    <strong>{{ $user->name }}</strong>
                                    @if($user->deleted_at)
                                        <br><small class="text-muted">
                                            <i class="fas fa-archive"></i> Archived {{ $user->deleted_at->diffForHumans() }}
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->role === 'admin')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-user-shield me-1"></i>Admin
                                        </span>
                                    @elseif($user->role === 'employer')
                                        <span class="badge bg-primary">
                                            <i class="fas fa-building me-1"></i>Employer
                                        </span>
                                    @elseif($user->role === 'seeker')
                                        <span class="badge bg-info">
                                            <i class="fas fa-user me-1"></i>Job Seeker
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($user->role) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->deleted_at)
                                        <span class="badge bg-warning">
                                            <i class="fas fa-archive me-1"></i>Archived
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->email_verified_at)
                                        <span class="badge bg-success">
                                            <i class="fas fa-check me-1"></i>Yes
                                        </span>
                                        <br>
                                        <small class="text-muted">{{ $user->email_verified_at->format('M d, Y') }}</small>
                                    @else
                                        <span class="badge bg-warning">
                                            <i class="fas fa-times me-1"></i>No
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $user->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.users.show', $user->id) }}" 
                                           class="btn btn-info"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($user->deleted_at)
                                            <!-- Restore Button for Archived Users -->
                                            <form action="{{ route('admin.users.restore', $user->id) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" 
                                                        class="btn btn-success" 
                                                        title="Restore User">
                                                    <i class="fas fa-undo"></i>
                                                </button>
                                            </form>
                                        @else
                                            <!-- Edit and Delete for Active Users -->
                                            <a href="{{ route('admin.users.edit', $user->id) }}" 
                                               class="btn btn-primary"
                                               title="Edit User">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Are you sure you want to archive this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        class="btn btn-warning" 
                                                        title="Archive User">
                                                    <i class="fas fa-archive"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Links -->
                <div class="mt-3">
                    {{ $users->appends(request()->except('page'))->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No users found.</p>
                    @if(request('search') || request('role') || request('verified') || request('status'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                            <i class="fas fa-redo me-1"></i>Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection