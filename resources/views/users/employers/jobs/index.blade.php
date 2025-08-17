@extends('layouts.dashboard')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>My Job Postings</h2>
                <a href="{{ route('employers.jobs.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Post New Job
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($jobs->count() > 0)
                <div class="row">
                    @foreach($jobs as $job)
                        <div class="col-md-6 mb-4">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title">{{ $job->job_title }}</h5>
                                        <span class="badge bg-{{ $job->status == 'open' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($job->status) }}
                                        </span>
                                    </div>
                                    
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $job->classification }}</h6>
                                    
                                    <p class="card-text">
                                        {{ Str::limit($job->description, 100) }}
                                    </p>
                                    
                                    <div class="mb-3">
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt"></i> {{ $job->location }}<br>
                                            <i class="fas fa-clock"></i> {{ ucfirst($job->employment_type) }}<br>
                                            <i class="fas fa-peso-sign"></i> ₱{{ number_format($job->salary, 2) }}
                                        </small>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">
                                            Posted: {{ $job->created_at->format('M d, Y') }}
                                        </small>
                                        <div>
                                            <a href="{{ route('employers.jobs.edit', $job->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
 
                                            <!-- form scured with proper delete-->            
                                             <form method="POST" action="{{ route('employers.jobs.delete', $job->id) }}" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this job?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-briefcase fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Job Postings Yet</h4>
                        <p class="text-muted">You haven't posted any jobs yet. Start by creating your first job posting!</p>
                        <a href="{{ route('employers.jobs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Post Your First Job
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
