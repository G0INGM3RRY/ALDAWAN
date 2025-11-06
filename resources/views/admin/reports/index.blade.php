@extends('layouts.admin')

@section('content')
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-chart-line me-2"></i>Reports & Analytics</h2>
            <p class="text-muted mb-0">System statistics and data visualization for {{ date('Y') }}</p>
        </div>
        <div>
            <button class="btn btn-outline-primary" onclick="window.print()">
                <i class="fas fa-print me-2"></i>Print Reports
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Users</p>
                            <h3 class="mb-0">{{ App\Models\User::count() }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-users text-primary fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-success">
                        <i class="fas fa-arrow-up"></i>
                        {{ App\Models\User::whereMonth('created_at', date('m'))->count() }} this month
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Jobs</p>
                            <h3 class="mb-0">{{ App\Models\Jobs::count() }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-briefcase text-success fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-success">
                        <i class="fas fa-arrow-up"></i>
                        {{ App\Models\Jobs::whereMonth('created_at', date('m'))->count() }} this month
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Applications</p>
                            <h3 class="mb-0">{{ App\Models\JobApplication::count() }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-file-alt text-info fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-success">
                        <i class="fas fa-arrow-up"></i>
                        {{ App\Models\JobApplication::whereMonth('created_at', date('m'))->count() }} this month
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Pending Verifications</p>
                            <h3 class="mb-0">
                                {{ 
                                    App\Models\CompanyVerification::where('status', 'pending')->count() +
                                    App\Models\FormalJobseekerVerification::where('status', 'pending')->count() +
                                    App\Models\InformalJobseekerVerification::where('status', 'pending')->count()
                                }}
                            </h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="fas fa-clock text-warning fa-2x"></i>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="fas fa-hourglass-half"></i>
                        Needs review
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row mb-4">
        <!-- User Registration Trends -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-user-plus me-2 text-primary"></i>User Registrations ({{ date('Y') }})</h5>
                    <small class="text-muted">Monthly user registration trends</small>
                </div>
                <div class="card-body">
                    <canvas id="userRegistrationChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Jobs by Status -->
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2 text-success"></i>Jobs by Status</h5>
                    <small class="text-muted">Current job distribution</small>
                </div>
                <div class="card-body">
                    <canvas id="jobsStatusChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Applications Chart -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-area me-2 text-info"></i>Job Applications ({{ date('Y') }})</h5>
                    <small class="text-muted">Monthly application trends</small>
                </div>
                <div class="card-body">
                    <canvas id="applicationsChart" style="max-height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Tables -->
    <div class="row">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>User Statistics</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><i class="fas fa-user-tie text-primary me-2"></i>Employers</td>
                                <td class="text-end"><strong>{{ App\Models\User::where('role', 'employer')->count() }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-user text-success me-2"></i>Job Seekers</td>
                                <td class="text-end"><strong>{{ App\Models\User::where('role', 'seeker')->count() }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-shield-alt text-danger me-2"></i>Admins</td>
                                <td class="text-end"><strong>{{ App\Models\User::where('role', 'admin')->count() }}</strong></td>
                            </tr>
                            <tr class="table-light">
                                <td><i class="fas fa-check-circle text-success me-2"></i>Verified Users</td>
                                <td class="text-end"><strong>{{ App\Models\User::whereNotNull('email_verified_at')->count() }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0"><i class="fas fa-table me-2"></i>Job Statistics</h5>
                </div>
                <div class="card-body">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><i class="fas fa-circle text-success me-2"></i>Open Jobs</td>
                                <td class="text-end"><strong>{{ App\Models\Jobs::where('status', 'open')->count() }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-circle text-danger me-2"></i>Closed Jobs</td>
                                <td class="text-end"><strong>{{ App\Models\Jobs::where('status', 'closed')->count() }}</strong></td>
                            </tr>
                            <tr>
                                <td><i class="fas fa-circle text-primary me-2"></i>Filled Jobs</td>
                                <td class="text-end"><strong>{{ App\Models\Jobs::where('status', 'filled')->count() }}</strong></td>
                            </tr>
                            <tr class="table-light">
                                <td><i class="fas fa-chart-bar text-info me-2"></i>Total Job Postings</td>
                                <td class="text-end"><strong>{{ App\Models\Jobs::count() }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Prepare data from Laravel
const usersByMonth = @json($reports['users_by_month']);
const jobsByStatus = @json($reports['jobs_by_status']);
const applicationsByMonth = @json($reports['applications_by_month']);

// Month names
const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// Prepare user registration data
const userMonthData = Array(12).fill(0);
usersByMonth.forEach(item => {
    userMonthData[item.month - 1] = item.count;
});

// User Registration Chart (Line Chart)
const userCtx = document.getElementById('userRegistrationChart').getContext('2d');
new Chart(userCtx, {
    type: 'line',
    data: {
        labels: monthNames,
        datasets: [{
            label: 'New Users',
            data: userMonthData,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 4,
            pointBackgroundColor: '#0d6efd'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
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

// Jobs by Status Chart (Doughnut Chart)
const jobStatusLabels = [];
const jobStatusData = [];
const jobStatusColors = {
    'open': '#198754',
    'closed': '#dc3545',
    'filled': '#0d6efd'
};

jobsByStatus.forEach(item => {
    jobStatusLabels.push(item.status.charAt(0).toUpperCase() + item.status.slice(1));
    jobStatusData.push(item.count);
});

const jobCtx = document.getElementById('jobsStatusChart').getContext('2d');
new Chart(jobCtx, {
    type: 'doughnut',
    data: {
        labels: jobStatusLabels,
        datasets: [{
            data: jobStatusData,
            backgroundColor: [
                jobStatusColors['open'] || '#198754',
                jobStatusColors['closed'] || '#dc3545',
                jobStatusColors['filled'] || '#0d6efd'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
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

// Prepare applications data
const appMonthData = Array(12).fill(0);
applicationsByMonth.forEach(item => {
    appMonthData[item.month - 1] = item.count;
});

// Applications Chart (Bar Chart)
const appCtx = document.getElementById('applicationsChart').getContext('2d');
new Chart(appCtx, {
    type: 'bar',
    data: {
        labels: monthNames,
        datasets: [{
            label: 'Applications',
            data: appMonthData,
            backgroundColor: 'rgba(13, 202, 240, 0.7)',
            borderColor: '#0dcaf0',
            borderWidth: 2,
            borderRadius: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: true,
                position: 'bottom'
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

// Print styles
const style = document.createElement('style');
style.innerHTML = `
    @media print {
        .sidebar, .btn, nav { display: none !important; }
        .card { page-break-inside: avoid; }
        canvas { max-height: none !important; }
    }
`;
document.head.appendChild(style);
</script>
@endpush
@endsection
