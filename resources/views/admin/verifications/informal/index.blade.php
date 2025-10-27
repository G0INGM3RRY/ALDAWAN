@extends('layouts.admin')

@section('title', 'Informal Jobseeker Verifications')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Informal Jobseeker Verifications</h2>
                <div class="badge bg-info">
                    Household/Manual Labor Job Applications
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4>{{ $verifications->where('status', 'pending')->count() }}</h4>
                            <small class="text-warning">Pending</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4>{{ $verifications->where('status', 'approved')->count() }}</h4>
                            <small class="text-success">Approved</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4>{{ $verifications->where('status', 'rejected')->count() }}</h4>
                            <small class="text-danger">Rejected</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-center">
                        <div class="card-body">
                            <h4>{{ $verifications->total() }}</h4>
                            <small class="text-primary">Total</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verifications Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Informal Verification Requests</h5>
                </div>
                <div class="card-body">
                    @if($verifications->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Jobseeker</th>
                                        <th>Email</th>
                                        <th>Submitted</th>
                                        <th>Documents</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($verifications as $verification)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $verification->jobseekerProfile->user->name }}</strong>
                                                </div>
                                            </td>
                                            <td>{{ $verification->jobseekerProfile->user->email }}</td>
                                            <td>{{ $verification->submitted_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="small">
                                                    <span class="badge bg-{{ $verification->basic_id_path ? 'success' : 'secondary' }}">
                                                        ID
                                                    </span>
                                                    <span class="badge bg-{{ $verification->barangay_clearance_path ? 'success' : 'secondary' }}">
                                                        Barangay
                                                    </span>
                                                    @if($verification->health_certificate_path)
                                                        <span class="badge bg-info">Health</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ 
                                                    $verification->status === 'approved' ? 'success' : 
                                                    ($verification->status === 'rejected' ? 'danger' : 'warning') 
                                                }}">
                                                    {{ ucfirst($verification->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.verifications.informal.show', $verification) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye"></i> Review
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $verifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No informal verification requests found.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection