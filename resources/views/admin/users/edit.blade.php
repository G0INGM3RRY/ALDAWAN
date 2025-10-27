@extends('layouts.admin')
@section('title', 'Edit User')
@section('page-title', 'Edit User')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5>Edit User Information</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">User Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="seeker" {{ old('role', $user->role) == 'seeker' ? 'selected' : '' }}>Job Seeker</option>
                            <option value="employer" {{ old('role', $user->role) == 'employer' ? 'selected' : '' }}>Employer</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="verification_status" class="form-label">Verification Status</label>
                        <select class="form-select" id="verification_status" name="verification_status">
                            <option value="">No Change</option>
                            <option value="pending" {{ old('verification_status', $user->verification_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="verified" {{ old('verification_status', $user->verification_status) == 'verified' ? 'selected' : '' }}>Verified</option>
                            <option value="rejected" {{ old('verification_status', $user->verification_status) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="account_status" class="form-label">Account Status</label>
                        <select class="form-select @error('account_status') is-invalid @enderror" id="account_status" name="account_status" required>
                            <option value="active" {{ old('account_status', $user->account_status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('account_status', $user->account_status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="suspended" {{ old('account_status', $user->account_status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                        </select>
                        @error('account_status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Update User</button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6>User Details</h6>
            </div>
            <div class="card-body">
                <p><strong>User ID:</strong> {{ $user->id }}</p>
                <p><strong>Member Since:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                <p><strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y') }}</p>
                <p><strong>Email Verified:</strong> 
                    @if($user->email_verified_at)
                        <span class="badge bg-success">Yes</span>
                    @else
                        <span class="badge bg-warning">No</span>
                    @endif
                </p>
                
                @if($user->jobseekerProfile)
                    <hr>
                    <h6>Job Seeker Profile</h6>
                    <p><strong>Type:</strong> {{ ucfirst($user->jobseekerProfile->job_seeker_type) }}</p>
                    <p><strong>Contact:</strong> {{ $user->jobseekerProfile->contactnumber ?? 'Not provided' }}</p>
                @endif
                
                @if($user->employer)
                    <hr>
                    <h6>Employer Profile</h6>
                    <p><strong>Company:</strong> {{ $user->employer->company_name }}</p>
                    <p><strong>Type:</strong> {{ ucfirst($user->employer->employer_type) }}</p>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6>Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm">View Full Profile</a>
                    
                    @if(!$user->is_verified)
                        <button class="btn btn-success btn-sm" onclick="verifyUser({{ $user->id }})">
                            Verify User
                        </button>
                    @endif
                    
                    <button class="btn btn-warning btn-sm" onclick="resetPassword({{ $user->id }})">
                        Reset Password
                    </button>
                    
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm w-100" 
                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            Delete User
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function verifyUser(userId) {
    if(confirm('Are you sure you want to verify this user?')) {
        // Add verification logic here
        alert('User verification functionality needs to be implemented');
    }
}

function resetPassword(userId) {
    if(confirm('Are you sure you want to reset this user\'s password?')) {
        // Add password reset logic here
        alert('Password reset functionality needs to be implemented');
    }
}
</script>
@endsection