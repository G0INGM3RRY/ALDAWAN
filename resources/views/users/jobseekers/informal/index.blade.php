@extends('layouts.dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Profile</h1>
        <a href="{{ route('jobseekers.informal.edit') }}" class="btn btn-primary">Edit Profile</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Profile Completion Status -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Profile Completion</h5>
        </div>
        <div class="card-body">
            @php
                $profile = Auth::user()->jobseekerProfile;
                $completed = 0;
                $total = 9; // Total sections for informal workers
                
                if($profile) {
                    // Personal Information (basic fields)
                    if($profile->first_name && $profile->last_name && $profile->birthday && $profile->sex) {
                        $completed++;
                    }
                    
                    // Contact Information
                    if($profile->contactnumber && $profile->email) {
                        $completed++;
                    }
                    
                    // Address Information  
                    if($profile->street && $profile->barangay && $profile->municipality && $profile->province) {
                        $completed++;
                    }
                    
                    // Civil Status & Religion
                    if($profile->civilstatus) {
                        $completed++;
                    }
                    
                    // Skills
                    if($profile->skills && $profile->skills->count() > 0) {
                        $completed++;
                    }
                    
                    // Work Experience (optional for informal, but counted if present)
                    if($profile->workExperiences && $profile->workExperiences->count() > 0) {
                        $completed++;
                    }
                    
                    // Employment Status
                    if($profile->employmentstatus) {
                        $completed++;
                    }
                    
                    // Job Preferences  
                    if(Auth::user()->jobPreferences && Auth::user()->jobPreferences->count() > 0) {
                        $completed++;
                    }
                    
                    // Photo
                    if($profile->photo) {
                        $completed++;
                    }
                }
                
                $percentage = $total > 0 ? ($completed / $total) * 100 : 0;
            @endphp
            
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold">Your Profile Progress</span>
                <span class="badge bg-primary fs-6">
                    {{ number_format($percentage, 0) }}%
                </span>
            </div>
            <div class="progress" style="height: 25px;">
                <div class="progress-bar bg-primary" 
                     role="progressbar" 
                     style="width: {{ $percentage }}%" 
                     aria-valuenow="{{ $percentage }}" 
                     aria-valuemin="0" 
                     aria-valuemax="100">
                    {{ number_format($percentage, 0) }}%
                </div>
            </div>
            
            @if($percentage < 100)
                <div class="mt-3">
                    <p class="text-muted mb-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Complete your profile to increase your chances of getting hired!
                    </p>
                    
                    <!-- Missing sections feedback -->
                    @php
                        $missingSections = [];
                        if($profile) {
                            if(!($profile->first_name && $profile->last_name && $profile->birthday && $profile->sex)) {
                                $missingSections[] = 'Complete basic personal information';
                            }
                            if(!($profile->contactnumber && $profile->email)) {
                                $missingSections[] = 'Add contact information';
                            }
                            if(!($profile->street && $profile->barangay && $profile->municipality && $profile->province)) {
                                $missingSections[] = 'Complete address details';
                            }
                            if(!$profile->civilstatus) {
                                $missingSections[] = 'Set civil status';
                            }
                            if(!($profile->skills && $profile->skills->count() > 0)) {
                                $missingSections[] = 'Add your skills';
                            }
                            if(!$profile->employmentstatus) {
                                $missingSections[] = 'Set employment status';
                            }
                            if(!(Auth::user()->jobPreferences && Auth::user()->jobPreferences->count() > 0)) {
                                $missingSections[] = 'Set job preferences';
                            }
                            if(!$profile->photo) {
                                $missingSections[] = 'Upload profile photo';
                            }
                        }
                    @endphp
                    
                    @if(count($missingSections) > 0)
                        <div class="alert alert-light border mt-2">
                            <small class="fw-bold"><i class="fas fa-tasks me-1"></i>Still needed:</small>
                            <ul class="mb-0 mt-2" style="font-size: 0.9em;">
                                @foreach($missingSections as $section)
                                    <li>{{ $section }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <a href="{{ route('jobseekers.informal.edit') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-edit me-1"></i>Complete Profile Now
                    </a>
                </div>
            @else
                <div class="alert alert-success mt-3 mb-0">
                    <i class="fas fa-check-circle me-2"></i>
                    <strong>Great job!</strong> Your profile is 100% complete. Keep it updated to attract more employers.
                </div>
            @endif
        </div>
    </div>

    <!-- Profile Overview Card -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Personal Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center">
                    @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->photo)
                        <img src="{{ asset('storage/' . Auth::user()->jobseekerProfile->photo) }}" 
                             alt="Profile Photo" class="img-fluid rounded-circle mb-3" style="max-width: 150px;">
                    @else
                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" 
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-3x text-muted"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>
                                @if(Auth::user()->jobseekerProfile && (Auth::user()->jobseekerProfile->first_name || Auth::user()->jobseekerProfile->last_name))
                                    {{ Auth::user()->jobseekerProfile->first_name ?? '' }} 
                                    {{ Auth::user()->jobseekerProfile->middle_name ?? '' }} 
                                    {{ Auth::user()->jobseekerProfile->last_name ?? '' }}
                                    {{ Auth::user()->jobseekerProfile->suffix ?? '' }}
                                @else
                                    <span class="text-muted">Name not set</span>
                                @endif
                            </h4>
                            
                            <p class="mb-2">
                                <strong>Email:</strong> {{ Auth::user()->email }}
                            </p>
                            
                            <p class="mb-2">
                                <strong>Contact Number:</strong> 
                                @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->contactnumber)
                                    {{ Auth::user()->jobseekerProfile->contactnumber }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                            
                            <p class="mb-2">
                                <strong>Birthday:</strong> 
                                @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->birthday)
                                    {{ \Carbon\Carbon::parse(Auth::user()->jobseekerProfile->birthday)->format('F d, Y') }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>Address:</strong><br>
                                @if(Auth::user()->jobseekerProfile && (Auth::user()->jobseekerProfile->street || Auth::user()->jobseekerProfile->barangay || Auth::user()->jobseekerProfile->municipality || Auth::user()->jobseekerProfile->province))
                                    @php $profile = Auth::user()->jobseekerProfile; @endphp
                                    {{ $profile->street ? $profile->street . ', ' : '' }}
                                    {{ $profile->barangay ? $profile->barangay . ', ' : '' }}
                                    {{ $profile->municipality ? $profile->municipality . ', ' : '' }}
                                    {{ $profile->province }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                            
                            <p class="mb-2">
                                <strong>Civil Status:</strong> 
                                @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->civilstatus)
                                    {{ ucfirst(Auth::user()->jobseekerProfile->civilstatus) }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                            
                            <p class="mb-2">
                                <strong>Gender:</strong> 
                                @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->sex)
                                    {{ ucfirst(Auth::user()->jobseekerProfile->sex) }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                            
                            <p class="mb-2">
                                <strong>Religion:</strong> 
                                @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->religion)
                                    {{ Auth::user()->jobseekerProfile->religion }}
                                @else
                                    <span class="text-muted">Not set</span>
                                @endif
                            </p>
                        </div>

                    </div>
                </div>
            </div>
       
        </div>
    </div>
    
    <!-- Skills Section -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Skills</h5>
        </div>
        <div class="card-body">
            @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->skills && Auth::user()->jobseekerProfile->skills->count() > 0)
                <div class="row">
                    @foreach(Auth::user()->jobseekerProfile->skills as $skill)
                        <div class="col-md-3 mb-2">
                            <span class="badge bg-primary p-2 w-100 text-start">
                                <i class="fas fa-tools me-1"></i>{{ $skill->name }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle me-2"></i>No skills added yet. 
                    <a href="{{ route('jobseekers.informal.edit') }}" class="fw-bold">Add your skills</a> to improve your profile.
                </p>
            @endif
        </div>
    </div>

    <!-- Employment Status & Additional Information -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Employment & Additional Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p class="mb-2">
                        <strong>Employment Status:</strong> 
                        @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->employmentstatus)
                            <span class="badge bg-{{ Auth::user()->jobseekerProfile->employmentstatus == 'employed' ? 'success' : 'primary' }}">
                                {{ ucfirst(Auth::user()->jobseekerProfile->employmentstatus) }}
                            </span>
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </p>
                    
                    <p class="mb-2">
                        <strong>4Ps Beneficiary:</strong> 
                        @if(Auth::user()->jobseekerProfile)
                            <span class="badge bg-{{ Auth::user()->jobseekerProfile->is_4ps ? 'success' : 'secondary' }}">
                                {{ Auth::user()->jobseekerProfile->is_4ps ? 'Yes' : 'No' }}
                            </span>
                        @else
                            <span class="text-muted">Not set</span>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Disabilities & Accommodations -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Disabilities & Accommodations</h5>
        </div>
        <div class="card-body">
            @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->disabilities && Auth::user()->jobseekerProfile->disabilities->count() > 0)
                <div class="row">
                    @foreach(Auth::user()->jobseekerProfile->disabilities as $disability)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="fw-bold">{{ $disability->name }}</h6>
                                @if($disability->pivot->accommodation_needs)
                                    <p class="text-muted mb-0">
                                        <strong>Accommodation needs:</strong> {{ $disability->pivot->accommodation_needs }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle me-2"></i>No disabilities or accommodations recorded. 
                    If you need accommodations, please <a href="{{ route('jobseekers.informal.edit') }}" class="fw-bold">update your profile</a>.
                </p>
            @endif
        </div>
    </div>

    <!-- Work Experience -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Work Experience</h5>
        </div>
        <div class="card-body">
            @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->workExperiences && Auth::user()->jobseekerProfile->workExperiences->count() > 0)
                <div class="timeline">
                    @foreach(Auth::user()->jobseekerProfile->workExperiences->sortByDesc('start_date') as $experience)
                        <div class="border-left border-primary pl-3 mb-4 position-relative" style="border-left-width: 3px !important;">
                            <div class="bg-primary rounded-circle position-absolute" style="width: 12px; height: 12px; left: -6px; top: 0;"></div>
                            <div class="ml-3">
                                <h6 class="fw-bold mb-1">{{ $experience->job_title }}</h6>
                                <p class="text-muted mb-1">{{ $experience->company_name }}</p>
                                <p class="text-muted mb-2">
                                    <small>
                                        {{ \Carbon\Carbon::parse($experience->start_date)->format('M Y') }} - 
                                        @if($experience->is_current)
                                            Present
                                        @elseif($experience->end_date)
                                            {{ \Carbon\Carbon::parse($experience->end_date)->format('M Y') }}
                                        @else
                                            Present
                                        @endif
                                        @if($experience->duration_in_months)
                                            ({{ $experience->duration_in_months }} months)
                                        @endif
                                    </small>
                                </p>
                                @if($experience->description)
                                    <p class="mb-0">{{ $experience->description }}</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle me-2"></i>No work experience added yet. 
                    <a href="{{ route('jobseekers.informal.edit') }}" class="fw-bold">Add your work history</a> to strengthen your profile.
                </p>
            @endif
        </div>
    </div>

    <!-- Job Preferences -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Job Preferences</h5>
        </div>
        <div class="card-body">
            @if(Auth::user()->jobPreferences && Auth::user()->jobPreferences->count() > 0)
                <div class="row">
                    @foreach(Auth::user()->jobPreferences as $preference)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3">
                                <h6 class="fw-bold">{{ $preference->preferred_job_title }}</h6>
                                <p class="mb-1">
                                    <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $preference->preferred_employment_type)) }}</span>
                                </p>
                                @if($preference->preferred_location)
                                    <p class="text-muted mb-0">
                                        Location: {{ $preference->preferred_location }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-muted mb-0">
                    <i class="fas fa-info-circle me-2"></i>No job preferences set. 
                    <a href="{{ route('jobseekers.informal.edit') }}" class="fw-bold">Set your preferences</a> to get better job matches.
                </p>
            @endif
        </div>
    </div>

    <style>
        .card-header {
            border: none;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }
        .badge {
            font-size: 0.9em;
        }
        .timeline .border-left {
            margin-left: 1rem;
        }
        .progress-bar {
            transition: width 0.6s ease;
        }
        .skill-badge {
            margin-bottom: 0.5rem;
            display: inline-block;
        }
        .experience-item {
            background: #f8f9fa;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 4px solid #007bff;
        }
        .section-divider {
            border-top: 2px solid #dee2e6;
            margin: 2rem 0;
        }
    </style>
@endsection