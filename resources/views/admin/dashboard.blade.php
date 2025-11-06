@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Statistics Cards -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Employers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_employers'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-building fa-2x text-gray-300"></i>
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
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Job Seekers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_jobseekers'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Jobs</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_jobs'] }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-briefcase fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Additional Stats -->
    <div class="col-xl-4 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Active Jobs</h6>
            </div>
            <div class="card-body">
                <div class="h4 mb-0 text-gray-800">{{ $stats['active_jobs'] }}</div>
                <div class="text-muted">Currently active job postings</div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Total Applications</h6>
            </div>
            <div class="card-body">
                <div class="h4 mb-0 text-gray-800">{{ $stats['total_applications'] }}</div>
                <div class="text-muted">Job applications submitted</div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-6">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Pending Verifications</h6>
            </div>
            <div class="card-body">
                <div class="h4 mb-0 text-gray-800 text-warning">{{ $stats['pending_verifications'] }}</div>
                <div class="text-muted">
                    <a href="{{ route('admin.verifications') }}" class="btn btn-sm btn-outline-primary">Review Now</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-block">
                            <i class="fas fa-users"></i> Manage Users
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.verifications') }}" class="btn btn-warning btn-block">
                            <i class="fas fa-check-circle"></i> Verifications
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.jobs') }}" class="btn btn-info btn-block">
                            <i class="fas fa-briefcase"></i> Manage Jobs
                        </a>
                    </div>
                    <div class="col-md-3 mb-2">
                        <a href="{{ route('admin.reports') }}" class="btn btn-success btn-block">
                            <i class="fas fa-chart-bar"></i> View Reports
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- User Growth Chart -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-chart-line me-2"></i>User Growth ({{ date('Y') }})
                </h6>
            </div>
            <div class="card-body">
                <canvas id="userGrowthChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>

    <!-- Jobs Status Chart -->
    <div class="col-lg-6 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-success">
                    <i class="fas fa-chart-pie me-2"></i>Jobs Distribution
                </h6>
            </div>
            <div class="card-body">
                <canvas id="jobsStatusChart" style="max-height: 250px;"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Section -->
<div class="row">
    <!-- Recent Users -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-user-plus me-2"></i>Recent Users
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentUsers as $user)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">{{ $user->name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                                    </small>
                                    <br>
                                    <span class="badge badge-sm 
                                        @if($user->role == 'admin') bg-danger
                                        @elseif($user->role == 'employer') bg-primary
                                        @else bg-success
                                        @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                                <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            <i class="fas fa-info-circle"></i> No recent users
                        </div>
                    @endforelse
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-primary">
                        View All Users <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Jobs -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-briefcase me-2"></i>Recent Jobs
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentJobs as $job)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ Str::limit($job->job_title, 30) }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-building me-1"></i>{{ $job->user->name ?? 'N/A' }}
                                    </small>
                                    <br>
                                    <span class="badge badge-sm 
                                        @if($job->status == 'open') bg-success
                                        @elseif($job->status == 'closed') bg-danger
                                        @else bg-primary
                                        @endif">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                </div>
                                <small class="text-muted">{{ $job->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            <i class="fas fa-info-circle"></i> No recent jobs
                        </div>
                    @endforelse
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.jobs') }}" class="btn btn-sm btn-outline-success">
                        View All Jobs <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Applications -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow h-100">
            <div class="card-header py-3 bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-file-alt me-2"></i>Recent Applications
                </h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($recentApplications as $app)
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="mb-1">{{ $app->user->name ?? 'N/A' }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-briefcase me-1"></i>{{ Str::limit($app->job->job_title ?? 'N/A', 25) }}
                                    </small>
                                    <br>
                                    <span class="badge badge-sm 
                                        @if($app->status == 'pending') bg-warning
                                        @elseif($app->status == 'accepted') bg-success
                                        @else bg-danger
                                        @endif">
                                        {{ ucfirst($app->status) }}
                                    </span>
                                </div>
                                <small class="text-muted">{{ $app->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <div class="list-group-item text-center text-muted">
                            <i class="fas fa-info-circle"></i> No recent applications
                        </div>
                    @endforelse
                </div>
                <div class="card-footer text-center">
                    <a href="{{ route('admin.jobs') }}" class="btn btn-sm btn-outline-info">
                        View All Applications <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Chart data from Laravel
const usersByMonth = @json($chartData['users_by_month']);
const jobsByStatus = @json($chartData['jobs_by_status']);

// Month names
const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// Prepare user growth data
const userMonthData = Array(12).fill(0);
usersByMonth.forEach(item => {
    userMonthData[item.month - 1] = item.count;
});

// User Growth Chart (Line Chart)
const userGrowthCtx = document.getElementById('userGrowthChart').getContext('2d');
new Chart(userGrowthCtx, {
    type: 'line',
    data: {
        labels: monthNames,
        datasets: [{
            label: 'New Users',
            data: userMonthData,
            borderColor: '#4e73df',
            backgroundColor: 'rgba(78, 115, 223, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#4e73df',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 14 },
                bodyFont: { size: 13 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});

// Jobs Status Chart (Doughnut Chart)
const jobStatusLabels = [];
const jobStatusData = [];
const statusColors = {
    'open': '#1cc88a',
    'closed': '#e74a3b',
    'filled': '#4e73df'
};
const chartColors = [];

jobsByStatus.forEach(item => {
    jobStatusLabels.push(item.status.charAt(0).toUpperCase() + item.status.slice(1));
    jobStatusData.push(item.count);
    chartColors.push(statusColors[item.status] || '#858796');
});

const jobsStatusCtx = document.getElementById('jobsStatusChart').getContext('2d');
new Chart(jobsStatusCtx, {
    type: 'doughnut',
    data: {
        labels: jobStatusLabels,
        datasets: [{
            data: jobStatusData,
            backgroundColor: chartColors,
            borderWidth: 3,
            borderColor: '#fff',
            hoverBorderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                padding: 12,
                titleFont: { size: 14 },
                bodyFont: { size: 13 }
            }
        }
    }
});
</script>
@endpush
@endsection