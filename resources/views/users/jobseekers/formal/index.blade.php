@extends('layouts.dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>My Profile</h1>
        <a href="{{ route('jobseekers.edit') }}" class="btn btn-primary">Edit Profile</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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
                            <h4>{{ Auth::user()->jobseekerProfile->first_name ?? 'Not Set' }} 
                                {{ Auth::user()->jobseekerProfile->middle_name ?? '' }} 
                                {{ Auth::user()->jobseekerProfile->last_name ?? '' }}
                                {{ Auth::user()->jobseekerProfile->suffix ?? '' }}</h4>
                            
                            <p class="text-muted mb-2">{{ Auth::user()->email }}</p>
                            
                            @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->contactnumber)
                                <p class="text-muted mb-2">{{ Auth::user()->jobseekerProfile->contactnumber }}</p>
                            @endif
                            
                            @if(Auth::user()->jobseekerProfile)
                                <p class="text-muted mb-2">
                                    Born: 
                                    @if(Auth::user()->jobseekerProfile->birthday)
                                        {{ \Carbon\Carbon::parse(Auth::user()->jobseekerProfile->birthday)->format('F d, Y') }}
                                    @else
                                        <span class="text-danger">Birthday not set</span>
                                    @endif
                                </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if(Auth::user()->jobseekerProfile)
                                @php $profile = Auth::user()->jobseekerProfile; @endphp
                                
                                @if($profile->street || $profile->barangay || $profile->municipality || $profile->province)
                                    <p class="text-muted mb-2">
                                        <strong>Address:</strong><br>
                                        {{ $profile->street ? $profile->street . ', ' : '' }}
                                        {{ $profile->barangay ? $profile->barangay . ', ' : '' }}
                                        {{ $profile->municipality ? $profile->municipality . ', ' : '' }}
                                        {{ $profile->province }}
                                    </p>
                                @endif
                                
                                @if($profile->civilstatus)
                                    <p class="text-muted mb-2">
                                        <strong>Civil Status:</strong> {{ ucfirst($profile->civilstatus) }}
                                    </p>
                                @endif
                                
                                @if($profile->sex)
                                    <p class="text-muted mb-2">
                                        <strong>Gender:</strong> {{ ucfirst($profile->sex) }}
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Skills Section -->
    @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->skills && Auth::user()->jobseekerProfile->skills->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-secondary text-white">
                <h5 class="mb-0">Professional Skills</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach(Auth::user()->jobseekerProfile->skills as $skill)
                        <div class="col-md-3 mb-2">
                            <span class="badge bg-primary p-2 w-100 text-start">
                                <i class="fas fa-{{ $skill->category == 'technical' ? 'code' : ($skill->category == 'soft' ? 'users' : 'language') }} me-1"></i>
                                {{ $skill->name }}
                                @if($skill->pivot && $skill->pivot->proficiency_level)
                                    <small class="d-block">{{ ucfirst($skill->pivot->proficiency_level) }}</small>
                                @endif
                            </span>
                        </div>
                    @endforeach
                </div>
                @if(Auth::user()->jobseekerProfile->skills->count() == 0)
                    <p class="text-muted mb-0">No skills added yet. <a href="{{ route('jobseekers.edit') }}">Add your skills</a> to improve your profile.</p>
                @endif
            </div>
        </div>
    @endif

    <!-- Education Background -->
    @if(Auth::user()->jobseekerProfile)
        @php $profile = Auth::user()->jobseekerProfile; @endphp
        @if($profile->education_level_id || $profile->institution_name || $profile->degree_field)
            <div class="card mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Education Background</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            @if($profile->educationLevel)
                                <p class="mb-2">
                                    <strong>Education Level:</strong> {{ $profile->educationLevel->name }}
                                </p>
                            @endif
                            
                            @if($profile->institution_name)
                                <p class="mb-2">
                                    <strong>Institution:</strong> {{ $profile->institution_name }}
                                </p>
                            @endif
                            
                            @if($profile->degree_field)
                                <p class="mb-2">
                                    <strong>Degree/Field:</strong> {{ $profile->degree_field }}
                                </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($profile->graduation_year)
                                <p class="mb-2">
                                    <strong>Graduation Year:</strong> {{ $profile->graduation_year }}
                                </p>
                            @endif
                            
                            @if(!is_null($profile->gpa) && $profile->gpa !== '')
                                <p class="mb-2">
                                    <strong>GPA:</strong>
                                    {{ is_numeric($profile->gpa) ? number_format((float) $profile->gpa, 2) : e($profile->gpa) }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif

    <!-- Work Experience -->
    @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->workExperiences && Auth::user()->jobseekerProfile->workExperiences->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Work Experience</h5>
            </div>
            <div class="card-body">
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
            </div>
        </div>
    @endif

    <!-- Employment Status & Additional Information -->
    @if(Auth::user()->jobseekerProfile)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Employment & Additional Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if(Auth::user()->jobseekerProfile->employmentstatus)
                            <p class="mb-2">
                                <strong>Employment Status:</strong> 
                                <span class="badge bg-{{ Auth::user()->jobseekerProfile->employmentstatus == 'employed' ? 'success' : 'warning' }}">
                                    {{ ucfirst(Auth::user()->jobseekerProfile->employmentstatus) }}
                                </span>
                            </p>
                        @endif
                        
                        @if(Auth::user()->jobseekerProfile->religion)
                            <p class="mb-2">
                                <strong>Religion:</strong> {{ Auth::user()->jobseekerProfile->religion }}
                            </p>
                        @endif
                        
                        <p class="mb-2">
                            <strong>4Ps Beneficiary:</strong> 
                            <span class="badge bg-{{ Auth::user()->jobseekerProfile->is_4ps ? 'success' : 'secondary' }}">
                                {{ Auth::user()->jobseekerProfile->is_4ps ? 'Yes' : 'No' }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>Total Experience:</strong> 
                            @if(Auth::user()->jobseekerProfile->workExperiences->count() > 0)
                                {{ number_format(Auth::user()->jobseekerProfile->total_experience_years, 1) }} years
                            @else
                                No experience recorded
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Disabilities & Accommodations -->
    @if(Auth::user()->jobseekerProfile && Auth::user()->jobseekerProfile->disabilities && Auth::user()->jobseekerProfile->disabilities->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-warning text-dark">
                <h5 class="mb-0">Disabilities & Accommodations</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach(Auth::user()->jobseekerProfile->disabilities as $disability)
                        <div class="col-md-6 mb-3">
                            <div class="border rounded p-3 bg-light">
                                <h6 class="fw-bold">{{ $disability->name }}</h6>
                                @if($disability->description)
                                    <p class="text-muted small mb-1">{{ $disability->description }}</p>
                                @endif
                                @if($disability->pivot->accommodation_needs)
                                    <p class="text-muted mb-0">
                                        <strong>Accommodation needs:</strong> {{ $disability->pivot->accommodation_needs }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Job Preferences -->
    @if(Auth::user()->jobPreferences && Auth::user()->jobPreferences->count() > 0)
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Job Preferences</h5>
            </div>
            <div class="card-body">
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
            </div>
        </div>
    @endif

    <!-- Profile Completion Status -->
    <div class="card">
        <div class="card-header bg-success text-white">
            <h5 class="mb-0">Profile Completion</h5>
        </div>
        <div class="card-body">
            @php
                $profile = Auth::user()->jobseekerProfile;
                $completed = 0;
                $total = 10; // Total sections
                
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
                    
                    // Education
                    if($profile->education_level_id || $profile->institution_name) {
                        $completed++;
                    }
                    
                    // Work Experience
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
                <span>Profile Completion</span>
                <span class="fw-bold">{{ number_format($percentage, 0) }}%</span>
            </div>
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: {{ $percentage }}%" 
                     aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            
            @if($percentage < 100)
                <div class="mt-3">
                    <p class="text-muted mb-2">Complete your profile to increase your chances of getting hired!</p>
                    
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
                            if(!($profile->education_level_id || $profile->institution_name)) {
                                $missingSections[] = 'Add education background';
                            }
                            if(!($profile->workExperiences && $profile->workExperiences->count() > 0)) {
                                $missingSections[] = 'Add work experience';
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
                        <div class="alert alert-info mt-2">
                            <small><strong>Still needed:</strong></small>
                            <ul class="mb-0 mt-1" style="font-size: 0.85em;">
                                @foreach($missingSections as $section)
                                    <li>{{ $section }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <a href="{{ route('jobseekers.edit') }}" class="btn btn-outline-primary btn-sm">
                        Complete Profile
                    </a>
                </div>
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