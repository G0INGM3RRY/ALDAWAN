
@extends('layouts.dashboard')


    
@section('content')
    <h1>Manage your personal profile</h1>
    
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 text-center">Manage your personal profile</h3>
                    <!-- Progress Steps -->
                    <div class="progress mt-3">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 16.67%" id="progress-bar"></div>
                    </div>
                    <div class="step-indicators d-flex justify-content-between mt-2" style="font-size: 0.85rem;">
                        <span class="step-indicator active" id="step-1">1. Personal</span>
                        <span class="step-indicator" id="step-2">2. Employment</span>
                        <span class="step-indicator" id="step-3">3. Job Prefs</span>
                        <span class="step-indicator" id="step-4">4. Education</span>
                        <span class="step-indicator" id="step-5">5. Experience</span>
                        <span class="step-indicator" id="step-6">6. Skills</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobseekers.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                            <!-- Personal information -->
                        <div id="section-personal-information" class="form-step active">

                            <div class="mb-3">
                                <label class="form-label">Job Seeker Type</label>
                                <p class="text-muted">Formal Worker - This cannot be changed after registration</p>
                                <input type="hidden" name="job_seeker_type" value="formal">
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $profile->first_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name', $profile->middle_name ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $profile->last_name ?? '') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <select name="suffix" class="form-control">
                                            <option value="">None</option>
                                            <option value="Jr." {{ (old('suffix', $profile->suffix ?? '') == 'Jr.') ? 'selected' : '' }}>Jr.</option>
                                            <option value="Sr." {{ (old('suffix', $profile->suffix ?? '') == 'Sr.') ? 'selected' : '' }}>Sr.</option>
                                            <option value="III" {{ (old('suffix', $profile->suffix ?? '') == 'III') ? 'selected' : '' }}>III</option>
                                            <option value="IV" {{ (old('suffix', $profile->suffix ?? '') == 'IV') ? 'selected' : '' }}>IV</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="birthday" class="form-label">Date of birth</label>
                                        <input type="date" name="birthday" id="birthday" class="form-control" value="{{ old('birthday', $profile->birthday ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="civilstatus" class="form-label">Civil Status</label>
                                        <select name="civilstatus" class="form-control">
                                            <option value="">Select</option>
                                            <option value="single" {{ (old('civilstatus', $profile->civilstatus ?? '') == 'single') ? 'selected' : '' }}>Single</option>
                                            <option value="married" {{ (old('civilstatus', $profile->civilstatus ?? '') == 'married') ? 'selected' : '' }}>Married</option>
                                            <option value="widowed" {{ (old('civilstatus', $profile->civilstatus ?? '') == 'widowed') ? 'selected' : '' }}>Widowed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sex" class="form-label">Sex</label>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="sex" id="formal_edit_male" value="male" class="form-check-input" {{ old('sex', $profile->sex ?? '') == 'male' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="formal_edit_male">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="sex" id="formal_edit_female" value="female" class="form-check-input" {{ old('sex', $profile->sex ?? '') == 'female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="formal_edit_female">Female</label>
                                            </div>
                                        </div>  
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Photo</label>
                                        <input type="file" name="photo" id="photo" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                        <small class="form-text text-muted">Upload your profile photo (JPG, PNG, max 2MB)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if($profile && $profile->photo)
                                         <div class="mt-2" id="current-photo">
                                             <img src="{{ asset('storage/' . $profile->photo) }}" alt="Current Photo" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                            <small class="text-muted d-block">Current: {{ $profile->photo }}</small>
                                         </div>
                                    @endif
                                    <!-- Photo preview for newly selected image -->
                                    <div class="mt-2" id="photo-preview-container" style="display: none;">
                                        <img id="photo-preview" src="" alt="Photo Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                        <small class="text-muted d-block">New Photo Preview</small>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">Address Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="street" class="form-label">Street</label>
                                        <input type="text" name="street" id="street" class="form-control" value="{{ old('street', $profile->street ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barangay" class="form-label">Barangay</label>
                                        <input type="text" name="barangay" id="barangay" class="form-control" value="{{ old('barangay', $profile->barangay ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="municipality" class="form-label">Municipality</label>
                                        <input type="text" name="municipality" id="municipality" class="form-control" value="{{ old('municipality', $profile->municipality ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="province" class="form-label">Province</label>
                                        <input type="text" name="province" id="province" class="form-control" value="{{ old('province', $profile->province ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="mt-4 mb-3">Contact Information</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="contactnumber" class="form-label">Contact Number</label>
                                        <input type="tel" name="contactnumber" id="contactnumber" class="form-control" 
                                               value="{{ old('contactnumber', $profile->contactnumber ?? '') }}"
                                               pattern="[0-9]{10,11}" placeholder="09XXXXXXXXX" 
                                               title="Enter 10 or 11 digit phone number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="religion" class="form-label">Religion</label>
                                        <input type="text" name="religion" id="religion" class="form-control" value="{{ old('religion', $profile->religion ?? '') }}">
                                    </div>
                                </div>
                            
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-muted">(Registered Email)</span></label>
                                        <input type="email" name="email" id="email" class="form-control bg-light" 
                                               value="{{ Auth::user()->email }}" readonly>
                                        <div class="form-text text-muted">
                                            <i class="fas fa-lock me-1"></i>This is your registered email. 
                                            <a href="{{ route('profile.edit') }}" class="text-primary">Change email in account settings</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 text-end">
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>
                    
                        
                        
                           
                            


                        <!-- Employment status -->
                        <div id="section-employment-status" class="form-step">
                            <div class="mb-3">
                                <label class="form-label styled-label">Disability</label>
                               
                                <div class="form-label">
                                    <small class="text-muted">Select all disabilities that apply to you for appropriate workplace accommodations.</small>
                                </div>
                                @php
                                    $userDisabilities = $profile && $profile->disabilities ? $profile->disabilities->pluck('id')->toArray() : [];
                                @endphp
                                <div class="row mt-2">
                                    @foreach($disabilities as $disability)
                                        <div class="col-md-3 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="disabilities[]" 
                                                       id="disability_{{ $disability->id }}" value="{{ $disability->id }}" 
                                                       {{ in_array($disability->id, $userDisabilities) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="disability_{{ $disability->id }}">{{ $disability->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="other_disabilities" class="form-label">Other disabilities (not listed above):</label>
                                            <input type="text" name="other_disabilities" id="other_disabilities" class="form-control" placeholder="Type other disabilities here, separated by commas">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">4PS Beneficiary?</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_4ps" id="is_4ps_yes" value="yes" {{ (old('is_4ps', $profile->is_4ps ?? false)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_4ps_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_4ps" id="is_4ps_no" value="no" {{ (old('is_4ps', $profile->is_4ps ?? false) === false) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_4ps_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employmentstatus" class="form-label">Employment Status</label>
                                        <select name="employmentstatus" id="employmentstatus" class="form-control">
                                            <option value="">Select</option>
                                            <option value="employed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'employed') ? 'selected' : '' }}>Employed</option>
                                            <option value="unemployed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'unemployed') ? 'selected' : '' }}>Unemployed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Prev</button>
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Job Preferences -->
                        <div id="section-job-preferences" class="form-step">
                            <h3>Job Preferences</h3>
                            <p class="text-muted">Specify your preferred job types and requirements. You can add multiple preferences.</p>
                            
                            <div id="job-preferences-container">
                                @if($jobPreferences && $jobPreferences->count() > 0)
                                    @foreach($jobPreferences as $index => $preference)
                                        <div class="job-preference-item border p-3 mb-3 rounded">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Preferred Job Title</label>
                                                        <input type="text" name="job_preferences[{{ $index }}][preferred_job_title]" class="form-control" 
                                                               value="{{ old('job_preferences.'.$index.'.preferred_job_title', $preference->preferred_job_title) }}" 
                                                               placeholder="e.g., Software Developer">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Job Classification</label>
                                                        <select name="job_preferences[{{ $index }}][preferred_classification]" class="form-control">
                                                            <option value="">Select Classification</option>
                                                            <option value="Information Technology" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                                            <option value="Customer Service" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                                                            <option value="Marketing" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                                            <option value="Administrative" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Administrative' ? 'selected' : '' }}>Administrative</option>
                                                            <option value="Creative" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Creative' ? 'selected' : '' }}>Creative</option>
                                                            <option value="Sales" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Sales' ? 'selected' : '' }}>Sales</option>
                                                            <option value="Finance" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                                            <option value="Healthcare" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                                            <option value="Education" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Education' ? 'selected' : '' }}>Education</option>
                                                            <option value="Manufacturing" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                                            <option value="Other" {{ old('job_preferences.'.$index.'.preferred_classification', $preference->preferred_classification) == 'Other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Min Salary (PHP)</label>
                                                        <input type="number" name="job_preferences[{{ $index }}][min_salary]" class="form-control" 
                                                               value="{{ old('job_preferences.'.$index.'.min_salary', $preference->min_salary) }}" 
                                                               step="0.01" placeholder="15000" min="0"
                                                               title="Enter minimum salary amount">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Max Salary (PHP)</label>
                                                        <input type="number" name="job_preferences[{{ $index }}][max_salary]" class="form-control" 
                                                               value="{{ old('job_preferences.'.$index.'.max_salary', $preference->max_salary) }}" 
                                                               step="0.01" placeholder="25000" min="0"
                                                               title="Enter maximum salary amount">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Preferred Location</label>
                                                        <input type="text" name="job_preferences[{{ $index }}][preferred_location]" class="form-control" 
                                                               value="{{ old('job_preferences.'.$index.'.preferred_location', $preference->preferred_location) }}" 
                                                               placeholder="e.g., Makati, Remote">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Employment Type</label>
                                                        <select name="job_preferences[{{ $index }}][preferred_employment_type]" class="form-control">
                                                            <option value="">Select Type</option>
                                                            <option value="full-time" {{ old('job_preferences.'.$index.'.preferred_employment_type', $preference->preferred_employment_type) == 'full-time' ? 'selected' : '' }}>Full-time</option>
                                                            <option value="part-time" {{ old('job_preferences.'.$index.'.preferred_employment_type', $preference->preferred_employment_type) == 'part-time' ? 'selected' : '' }}>Part-time</option>
                                                            <option value="contract" {{ old('job_preferences.'.$index.'.preferred_employment_type', $preference->preferred_employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                                            <option value="freelance" {{ old('job_preferences.'.$index.'.preferred_employment_type', $preference->preferred_employment_type) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                                            <option value="internship" {{ old('job_preferences.'.$index.'.preferred_employment_type', $preference->preferred_employment_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger remove-preference {{ $jobPreferences->count() <= 1 ? 'hidden' : '' }}">Remove Preference</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="job-preference-item border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Preferred Job Title</label>
                                                    <input type="text" name="job_preferences[0][preferred_job_title]" class="form-control w-75" placeholder="e.g., Software Developer">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Job Classification</label>
                                                    <select name="job_preferences[0][preferred_classification]" class="form-control w-75">
                                                        <option value="">Select Classification</option>
                                                        <option value="Information Technology">Information Technology</option>
                                                        <option value="Customer Service">Customer Service</option>
                                                        <option value="Marketing">Marketing</option>
                                                        <option value="Administrative">Administrative</option>
                                                        <option value="Creative">Creative</option>
                                                        <option value="Sales">Sales</option>
                                                        <option value="Finance">Finance</option>
                                                        <option value="Healthcare">Healthcare</option>
                                                        <option value="Education">Education</option>
                                                        <option value="Manufacturing">Manufacturing</option>
                                                        <option value="Other">Other</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Min Salary (PHP)</label>
                                                    <input type="number" name="job_preferences[0][min_salary]" class="form-control w-75" step="0.01" placeholder="15000" min="0" title="Enter minimum salary amount">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Max Salary (PHP)</label>
                                                    <input type="number" name="job_preferences[0][max_salary]" class="form-control w-75" step="0.01" placeholder="25000" min="0" title="Enter maximum salary amount">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Preferred Location</label>
                                                    <input type="text" name="job_preferences[0][preferred_location]" class="form-control w-75" placeholder="e.g., Makati, Remote">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Employment Type</label>
                                                    <select name="job_preferences[0][preferred_employment_type]" class="form-control w-75">
                                                        <option value="">Select Type</option>
                                                        <option value="full-time">Full-time</option>
                                                        <option value="part-time">Part-time</option>
                                                        <option value="contract">Contract</option>
                                                        <option value="freelance">Freelance</option>
                                                        <option value="internship">Internship</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6 d-flex align-items-end">
                                                <button type="button" class="btn btn-outline-danger remove-preference hidden">Remove Preference</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary" id="add-job-preference">
                                + Add Another Job Preference
                            </button>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Prev</button>
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                            
                        </div>

                        <!-- Educational background -->
                        <div id="section-educational-background" class="form-step">
                            <h3>Educational Background</h3>
                            <p class="text-muted">Add all your educational attainment from elementary to latest. You can add multiple entries.</p>
                            
                            <div id="education-container">
                                @php
                                    $educationRecords = old('education', $profile->education ?? []);
                                    $educationRecords = is_array($educationRecords) && count($educationRecords) > 0 ? $educationRecords : [[]];
                                @endphp
                                
                                @foreach($educationRecords as $index => $education)
                                    <div class="education-item border p-3 mb-3 rounded bg-light">
                                        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3">
                                            <h6 class="mb-2 mb-sm-0">
                                                <i class="fas fa-graduation-cap me-2"></i>
                                                @if(!empty($education['level_id']))
                                                    @php
                                                        $levelName = $educationLevels->find($education['level_id'])->name ?? 'Education Record';
                                                    @endphp
                                                    {{ $levelName }}
                                                @else
                                                    Education Record
                                                @endif
                                            </h6>
                                            @if($index > 0)
                                                <button type="button" class="btn btn-sm btn-danger remove-education">
                                                    <i class="fas fa-trash"></i><span class="d-none d-sm-inline ms-1">Remove</span>
                                                </button>
                                            @endif
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-12 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Education Level</label>
                                                    <select name="education[{{ $index }}][level_id]" class="form-control">
                                                        <option value="">Select Level</option>
                                                        @foreach($educationLevels as $level)
                                                            <option value="{{ $level->id }}" 
                                                                    data-level-name="{{ $level->name }}"
                                                                    data-level-order="{{ $level->id }}"
                                                                    {{ ($education['level_id'] ?? '') == $level->id ? 'selected' : '' }}>
                                                                {{ $level->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">School/Institution Name</label>
                                                    <input type="text" name="education[{{ $index }}][institution_name]" 
                                                           class="form-control" 
                                                           value="{{ $education['institution_name'] ?? '' }}" 
                                                           placeholder="e.g., Sample Elementary School">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Year Graduated/Completed</label>
                                                    <input type="number" name="education[{{ $index }}][graduation_year]" 
                                                           class="form-control" 
                                                           value="{{ $education['graduation_year'] ?? '' }}" 
                                                           placeholder="e.g., 2020" min="1950" max="{{ date('Y') + 10 }}"
                                                           title="Enter year between 1950 and {{ date('Y') + 10 }}">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Degree/Field of Study</label>
                                                    <input type="text" name="education[{{ $index }}][degree_field]" 
                                                           class="form-control" 
                                                           value="{{ $education['degree_field'] ?? '' }}" 
                                                           placeholder="e.g., BS Computer Science">
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Honors/Awards</label>
                                                    <input type="text" name="education[{{ $index }}][honors]" 
                                                           class="form-control" 
                                                           value="{{ $education['honors'] ?? '' }}" 
                                                           placeholder="e.g., With Honors">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            <button type="button" id="add-education" class="btn btn-success mb-3 w-100 w-sm-auto">
                                <i class="fas fa-plus"></i> Add Another Education Entry
                            </button>
                            
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Prev</button>
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Work experience -->
                        <div id="section-work-experience" class="form-step">
                            <h3>Work Experience</h3>
                            <p class="text-muted">Add your work experiences. You can add multiple entries.</p>
                            
                            <div id="work-experiences-container">
                                @if($profile && $profile->workExperiences && $profile->workExperiences->count() > 0)
                                    @foreach($profile->workExperiences as $index => $workExp)
                                        <div class="work-experience-item border p-3 mb-3 rounded">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Company Name</label>
                                                        <input type="text" name="work_experiences[{{ $index }}][company_name]" 
                                                               class="form-control" 
                                                               value="{{ old('work_experiences.'.$index.'.company_name', $workExp->company_name) }}" 
                                                               placeholder="Company name">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Job Title</label>
                                                        <input type="text" name="work_experiences[{{ $index }}][job_title]" 
                                                               class="form-control" 
                                                               value="{{ old('work_experiences.'.$index.'.job_title', $workExp->job_title) }}" 
                                                               placeholder="Position held">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Start Date</label>
                                                        <input type="date" name="work_experiences[{{ $index }}][start_date]" 
                                                               class="form-control" 
                                                               value="{{ old('work_experiences.'.$index.'.start_date', $workExp->start_date ? $workExp->start_date->format('Y-m-d') : '') }}">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">End Date</label>
                                                        <input type="date" name="work_experiences[{{ $index }}][end_date]" 
                                                               class="form-control" 
                                                               value="{{ old('work_experiences.'.$index.'.end_date', $workExp->end_date ? $workExp->end_date->format('Y-m-d') : '') }}">
                                                        <small class="text-muted">Leave blank if current position</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <div class="form-check mt-4">
                                                            <input type="checkbox" name="work_experiences[{{ $index }}][is_current]" 
                                                                   class="form-check-input" id="current_{{ $index }}" 
                                                                   value="1" {{ old('work_experiences.'.$index.'.is_current', $workExp->is_current) ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="current_{{ $index }}">Current Position</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <label class="form-label">Description (Optional)</label>
                                                        <textarea name="work_experiences[{{ $index }}][description]" 
                                                                  class="form-control" rows="2" 
                                                                  placeholder="Brief description of responsibilities">{{ old('work_experiences.'.$index.'.description', $workExp->description) }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end">
                                                    <button type="button" class="btn btn-outline-danger remove-work-experience">Remove</button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="work-experience-item border p-3 mb-3 rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Company Name</label>
                                                    <input type="text" name="work_experiences[0][company_name]" 
                                                           class="form-control" placeholder="Company name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Job Title</label>
                                                    <input type="text" name="work_experiences[0][job_title]" 
                                                           class="form-control" placeholder="Position held">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Start Date</label>
                                                    <input type="date" name="work_experiences[0][start_date]" class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">End Date</label>
                                                    <input type="date" name="work_experiences[0][end_date]" class="form-control">
                                                    <small class="text-muted">Leave blank if current position</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <div class="form-check mt-4">
                                                        <input type="checkbox" name="work_experiences[0][is_current]" 
                                                               class="form-check-input" id="current_0" value="1">
                                                        <label class="form-check-label" for="current_0">Current Position</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="mb-3">
                                                    <label class="form-label">Description (Optional)</label>
                                                    <textarea name="work_experiences[0][description]" 
                                                              class="form-control" rows="2" 
                                                              placeholder="Brief description of responsibilities"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary" id="add-work-experience">
                                + Add Work Experience
                            </button>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Prev</button>
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>

                            
                        </div>

                        <!-- Skills -->
                        <div id="section-skills" class="form-step">
                            <h3>Skills</h3>
                            <label class="form-label">Select your skills</label>
                            @php
                                $userSkills = $profile && $profile->skills ? $profile->skills->pluck('id')->toArray() : [];
                            @endphp
                            <div class="mb-2 row">
                                @foreach($skills->chunk(3) as $skillChunk)
                                    <div class="col-md-4">
                                        @foreach($skillChunk as $skill)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="skills[]" 
                                                       id="skill_{{ $skill->id }}" value="{{ $skill->id }}" 
                                                       {{ in_array($skill->id, $userSkills) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="skill_{{ $skill->id }}">{{ $skill->name }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div>
                            <div class="mb-2">
                                <label for="skills_other" class="form-label">Other skills (not listed above):</label>
                                <input type="text" name="skills_other" id="skills_other" class="form-control w-75" placeholder="Type other skills here, separated by commas">
                            </div>
                            <small class="form-text text-muted">
                                Please check all that apply and add any other skills not listed. Use common terms to help employers find you more easily.<br>
                                <i class="fas fa-info-circle text-primary"></i> <strong>Smart Skills Display:</strong> We show the 20 most popular and relevant skills. Custom skills you add will be included in future selections based on usage.
                            </small>

                            <!-- Verification Documents Section -->
                            <div class="mt-5">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-file-upload me-2"></i>Document Verification
                                    @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->formalVerification)
                                        <span class="badge bg-{{ auth()->user()->jobseekerProfile->formalVerification->status === 'approved' ? 'success' : 
                                                                 (auth()->user()->jobseekerProfile->formalVerification->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(auth()->user()->jobseekerProfile->formalVerification->status) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Not Submitted</span>
                                    @endif
                                </h5>
                                
                                @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->formalVerification)
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Verification Status:</strong> 
                                        @if(auth()->user()->jobseekerProfile->formalVerification->status === 'approved')
                                            Your documents have been verified and approved.
                                        @elseif(auth()->user()->jobseekerProfile->formalVerification->status === 'rejected')
                                            Your documents were rejected. Please upload new documents.
                                            @if(auth()->user()->jobseekerProfile->formalVerification->rejection_reason)
                                                <br><strong>Reason:</strong> {{ auth()->user()->jobseekerProfile->formalVerification->rejection_reason }}
                                            @endif
                                        @else
                                            Your documents are under review by our admin team.
                                        @endif
                                    </div>
                                @else
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        <strong>Verification Recommended:</strong> Upload your verification documents to get verified and access more job opportunities.
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-id-card me-1"></i>Government-issued ID
                                            </label>
                                            <input type="file" name="government_id" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="form-text">
                                                Upload a clear photo of your government ID (Driver's License, UMID, SSS, etc.)
                                            </div>
                                            @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->formalVerification && auth()->user()->jobseekerProfile->formalVerification->government_id_path)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Current file uploaded
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-graduation-cap me-1"></i>Educational Certificate/Diploma
                                            </label>
                                            <input type="file" name="educational_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="form-text">
                                                Upload your highest educational attainment certificate or diploma
                                            </div>
                                            @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->formalVerification && auth()->user()->jobseekerProfile->formalVerification->educational_document_path)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Current file uploaded
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-shield-alt me-1"></i>NBI Clearance
                                            </label>
                                            <input type="file" name="nbi_clearance" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="form-text">
                                                Upload a valid NBI clearance for background verification
                                            </div>
                                            @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->formalVerification && auth()->user()->jobseekerProfile->formalVerification->nbi_clearance_path)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Current file uploaded
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-certificate me-1"></i>Professional Skills Certificate 
                                                <span class="text-muted">(Optional)</span>
                                            </label>
                                            <input type="file" name="skills_certificate" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="form-text">
                                                Upload any professional certification or skills certificate you have
                                            </div>
                                            @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->formalVerification && auth()->user()->jobseekerProfile->formalVerification->skills_certificate_path)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Current file uploaded
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                            </div>
                            
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Prev</button>
                                <button type="submit" class="btn btn-success btn-lg">Update Profile</button>
                            </div>
                        </div>                       
                    </form>
                </div>
            </div>
        </div>
    </div>
    <style>
        .form-step{
            display: none;
        }
        .form-step.active{
            display: block;
        }
        .step-indicators .step-indicator {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }
        .step-indicators .step-indicator.active {
            color: #0d6efd !important;
            font-weight: 700;
        }
    </style>

    <script>
        
        let preferenceCount = {{ $jobPreferences ? $jobPreferences->count() : 1 }};
        
        document.getElementById('add-job-preference').addEventListener('click', function() {
            const container = document.getElementById('job-preferences-container');
            const newPreference = createJobPreferenceItem(preferenceCount);
            container.insertAdjacentHTML('beforeend', newPreference);
            
            // Show remove buttons if more than one preference
            const removeButtons = document.querySelectorAll('.remove-preference');
            if (removeButtons.length > 1) {
                removeButtons.forEach(btn => btn.style.display = 'block');
            }
            
            preferenceCount++;
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-preference')) {
                e.target.closest('.job-preference-item').remove();
                
                // Hide remove buttons if only one preference remains
                const remainingItems = document.querySelectorAll('.job-preference-item');
                if (remainingItems.length === 1) {
                    const lastRemoveBtn = document.querySelector('.remove-preference');
                    if (lastRemoveBtn) lastRemoveBtn.style.display = 'none';
                }
            }
        });
        
        function createJobPreferenceItem(index) {
            return `
                <div class="job-preference-item border p-3 mb-3 rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Preferred Job Title</label>
                                <input type="text" name="job_preferences[${index}][preferred_job_title]" class="form-control w-75" placeholder="e.g., Software Developer">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Job Classification</label>
                                <select name="job_preferences[${index}][preferred_classification]" class="form-control w-75">
                                    <option value="">Select Classification</option>
                                    <option value="Information Technology">Information Technology</option>
                                    <option value="Customer Service">Customer Service</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Administrative">Administrative</option>
                                    <option value="Creative">Creative</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Healthcare">Healthcare</option>
                                    <option value="Education">Education</option>
                                    <option value="Manufacturing">Manufacturing</option>
                                    <option value="Other">Other</option>
                                </select>
                                    </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Min Salary (PHP)</label>
                                <input type="number" name="job_preferences[${index}][min_salary]" class="form-control w-75" step="0.01" placeholder="15000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Max Salary (PHP)</label>
                                <input type="number" name="job_preferences[${index}][max_salary]" class="form-control w-75" step="0.01" placeholder="25000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Preferred Location</label>
                                <input type="text" name="job_preferences[${index}][preferred_location]" class="form-control w-75" placeholder="e.g., Makati, Remote">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Employment Type</label>
                                <select name="job_preferences[${index}][preferred_employment_type]" class="form-control w-75">
                                    <option value="">Select Type</option>
                                    <option value="full-time">Full-time</option>
                                    <option value="part-time">Part-time</option>
                                    <option value="contract">Contract</option>
                                    <option value="freelance">Freelance</option>
                                    <option value="internship">Internship</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger remove-preference">Remove Preference</button>
                        </div>
                    </div>
                </div>
            `;
        }
        
        // Work Experience Management
        let workExperienceCount = {{ $profile && $profile->workExperiences ? $profile->workExperiences->count() : 1 }};
        
        document.getElementById('add-work-experience').addEventListener('click', function() {
            const container = document.getElementById('work-experiences-container');
            const newWorkExperience = createWorkExperienceItem(workExperienceCount);
            container.insertAdjacentHTML('beforeend', newWorkExperience);
            workExperienceCount++;
        });
        
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-work-experience')) {
                e.target.closest('.work-experience-item').remove();
            }
        });
        
        function createWorkExperienceItem(index) {
            return `
                <div class="work-experience-item border p-3 mb-3 rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Company Name</label>
                                <input type="text" name="work_experiences[${index}][company_name]" 
                                       class="form-control" placeholder="Company name">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Job Title</label>
                                <input type="text" name="work_experiences[${index}][job_title]" 
                                       class="form-control" placeholder="Position held">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Start Date</label>
                                <input type="date" name="work_experiences[${index}][start_date]" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">End Date</label>
                                <input type="date" name="work_experiences[${index}][end_date]" class="form-control">
                                <small class="text-muted">Leave blank if current position</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input type="checkbox" name="work_experiences[${index}][is_current]" 
                                           class="form-check-input" id="current_${index}" value="1">
                                    <label class="form-check-label" for="current_${index}">Current Position</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Description (Optional)</label>
                                <textarea name="work_experiences[${index}][description]" 
                                          class="form-control" rows="2" 
                                          placeholder="Brief description of responsibilities"></textarea>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-outline-danger remove-work-experience">Remove</button>
                        </div>
                    </div>
                </div>
            `;
        }

        //step by step 
        let currentStep = 0;
        const steps = document.querySelectorAll(".form-step");
        const progressBar = document.getElementById("progress-bar");
        const stepIndicators = document.querySelectorAll(".step-indicator");

        function showStep(step){
            steps.forEach((s, i)=> {
                s.classList.remove("active");
                if(i === step){
                    s.classList.add("active");
                }

            });
            
            // Update progress bar
            const progress = ((step + 1) / steps.length) * 100;
            progressBar.style.width = progress + "%";
            
            // Update step indicators
            stepIndicators.forEach((indicator, i) => {
                if (i <= step) {
                    indicator.classList.add("active");
                } else {
                    indicator.classList.remove("active");
                }
            });
        }

        function prevStep(){
            if(currentStep > 0){
                currentStep--;
                showStep(currentStep);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }

        function nextStep(){
            if(currentStep < steps.length-1){
                currentStep++;
                showStep(currentStep);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
        showStep(currentStep);

        // Form submission validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            const emptyFields = [];
            const sectionNames = {
                'section-personal-information': 'Section 1: Personal Information',
                'section-employment': 'Section 2: Employment Status',
                'section-job-preferences': 'Section 3: Job Preferences',
                'section-education': 'Section 4: Education',
                'section-work-experience': 'Section 5: Work Experience',
                'section-skills': 'Section 6: Skills & Verification'
            };

            requiredFields.forEach(field => {
                const isVisible = field.offsetParent !== null;
                const isEmpty = !field.value || field.value.trim() === '';
                
                if (isVisible && isEmpty) {
                    const section = field.closest('.form-step');
                    const sectionId = section ? section.id : 'unknown';
                    const sectionName = sectionNames[sectionId] || 'Unknown Section';
                    const fieldLabel = field.closest('.mb-3')?.querySelector('label')?.textContent.replace('*', '').trim() || field.name;
                    
                    emptyFields.push({
                        section: sectionName,
                        field: fieldLabel,
                        element: field
                    });
                }
            });

            if (emptyFields.length > 0) {
                e.preventDefault();
                
                const groupedFields = {};
                emptyFields.forEach(item => {
                    if (!groupedFields[item.section]) {
                        groupedFields[item.section] = [];
                    }
                    groupedFields[item.section].push(item.field);
                });

                let message = 'Please fill in the following required fields:\n\n';
                Object.keys(groupedFields).forEach(section => {
                    message += `${section}:\n`;
                    groupedFields[section].forEach(field => {
                        message += `  • ${field}\n`;
                    });
                    message += '\n';
                });

                alert(message);

                if (emptyFields[0].element) {
                    emptyFields[0].element.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    emptyFields[0].element.focus();
                }
            }
        });

        // Photo upload real-time preview
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file type
                if (!file.type.match('image/jpeg') && !file.type.match('image/png') && !file.type.match('image/jpg')) {
                    alert('Please select a valid image file (JPG or PNG)');
                    e.target.value = '';
                    return;
                }
                
                // Validate file size (2MB max)
                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    e.target.value = '';
                    return;
                }
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').src = e.target.result;
                    document.getElementById('photo-preview-container').style.display = 'block';
                    // Hide current photo if exists
                    const currentPhoto = document.getElementById('current-photo');
                    if (currentPhoto) {
                        currentPhoto.style.display = 'none';
                    }
                };
                reader.readAsDataURL(file);
            } else {
                // If file is cleared, hide preview and show current photo again
                document.getElementById('photo-preview-container').style.display = 'none';
                const currentPhoto = document.getElementById('current-photo');
                if (currentPhoto) {
                    currentPhoto.style.display = 'block';
                }
            }
        });

        // Education entries management
        let educationCount = {{ count($profile->education ?? [[]]) }};
        const educationLevels = @json($educationLevels);

        document.getElementById('add-education').addEventListener('click', function() {
            const container = document.getElementById('education-container');
            const newEducation = createEducationItem(educationCount);
            container.insertAdjacentHTML('beforeend', newEducation);
            educationCount++;
            updateEducationNumbers();
        });

        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-education') || e.target.closest('.remove-education')) {
                const button = e.target.classList.contains('remove-education') ? e.target : e.target.closest('.remove-education');
                button.closest('.education-item').remove();
                updateEducationNumbers();
            }
        });

        function createEducationItem(index) {
            let levelOptions = '<option value="">Select Level</option>';
            educationLevels.forEach(level => {
                levelOptions += `<option value="${level.id}" data-level-name="${level.name}" data-level-order="${level.id}">${level.name}</option>`;
            });

            return `
                <div class="education-item border p-3 mb-3 rounded bg-light">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3">
                        <h6 class="mb-2 mb-sm-0">
                            <i class="fas fa-graduation-cap me-2"></i>
                            <span class="education-title">Education Record</span>
                        </h6>
                        <button type="button" class="btn btn-sm btn-danger remove-education">
                            <i class="fas fa-trash"></i><span class="d-none d-sm-inline ms-1">Remove</span>
                        </button>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Education Level</label>
                                <select name="education[${index}][level_id]" class="form-control education-level-select">
                                    ${levelOptions}
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label class="form-label">School/Institution Name</label>
                                <input type="text" name="education[${index}][institution_name]" 
                                       class="form-control" 
                                       placeholder="e.g., Sample Elementary School">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Year Graduated/Completed</label>
                                <input type="number" name="education[${index}][graduation_year]" 
                                       class="form-control" 
                                       placeholder="e.g., 2020" min="1950" max="{{ date('Y') + 10 }}">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Degree/Field of Study</label>
                                <input type="text" name="education[${index}][degree_field]" 
                                       class="form-control" 
                                       placeholder="e.g., BS Computer Science">
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Honors/Awards</label>
                                <input type="text" name="education[${index}][honors]" 
                                       class="form-control" 
                                       placeholder="e.g., With Honors">
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Update education title when level is selected
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('education-level-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const levelName = selectedOption.getAttribute('data-level-name');
                if (levelName) {
                    const educationItem = e.target.closest('.education-item');
                    const titleSpan = educationItem.querySelector('.education-title');
                    if (titleSpan) {
                        titleSpan.textContent = levelName;
                    }
                }
            }
        });

        function updateEducationNumbers() {
            // No longer needed since we're using level names instead of numbers
            // Keeping function for compatibility but leaving it empty
        }


    </script>
@endsection