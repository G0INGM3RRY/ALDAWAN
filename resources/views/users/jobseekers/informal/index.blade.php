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
                $completionFields = [
                    'first_name', 'last_name', 'birthday', 'sex', 'contactnumber', 
                    'street', 'barangay', 'municipality', 'province'
                ];
                $completed = 0;
                $total = count($completionFields);
                
                if($profile) {
                    foreach($completionFields as $field) {
                        if(!empty($profile->$field)) $completed++;
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
                    <a href="{{ route('jobseekers.informal.edit') }}" class="btn btn-outline-primary btn-sm">
                        Complete Profile
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection