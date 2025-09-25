
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
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 text-center">Manage your personal profile</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobseekers.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                
                        <!-- Personal information -->
                        <div id="section-personal-information">
                            <div class="mb-3">
                                <label class="form-label">Job Seeker Type</label>
                                <p class="text-muted">Formal Worker - This cannot be changed after registration</p>
                                <input type="hidden" name="job_seeker_type" value="formal">
                            </div>
                            
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control w-75" value="{{ old('first_name', $profile->first_name ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control w-75" value="{{ old('middle_name', $profile->middle_name ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control w-75" value="{{ old('last_name', $profile->last_name ?? '') }}" required>
                            </div>
                            <div class="mb-3">
                                <label for="suffix" class="form-label">Suffix</label>
                                <select name="suffix" class="form-control w-75">
                                    <option value="">None</option>
                                    <option value="Jr." {{ (old('suffix', $profile->suffix ?? '') == 'Jr.') ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ (old('suffix', $profile->suffix ?? '') == 'Sr.') ? 'selected' : '' }}>Sr.</option>
                                    <option value="III" {{ (old('suffix', $profile->suffix ?? '') == 'III') ? 'selected' : '' }}>III</option>
                                    <option value="IV" {{ (old('suffix', $profile->suffix ?? '') == 'IV') ? 'selected' : '' }}>IV</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="birthday" class="form-label">Date of birth</label>
                                <input type="date" name="birthday" id="birthday" class="form-control w-75" value="{{ old('birthday', $profile->birthday ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select name="sex" class="form-control w-75">
                                    <option value="">None</option>
                                    <option value="male" {{ (old('sex', $profile->sex ?? '') == 'male') ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ (old('sex', $profile->sex ?? '') == 'female') ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="photo" class="form-label">Photo</label>
                                <input type="file" name="photo" id="photo" class="form-control w-75">
                                @if($profile && $profile->photo)
                                     <div class="mt-2">
                                         <img src="{{ asset('storage/' . $profile->photo) }}" alt="Current Photo" style="max-width: 200px;">
                                        <small class="text-muted d-block">Current: {{ $profile->photo }}</small>
                                     </div>
                                @endif
                            </div>
                            <div class="mb-3">
                                <label for="civilstatus" class="form-label">Civil Status</label>
                                <select name="civilstatus" class="form-control w-75">
                                    <option value="">Select</option>
                                    <option value="single" {{ (old('civilstatus', $profile->civilstatus ?? '') == 'single') ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ (old('civilstatus', $profile->civilstatus ?? '') == 'married') ? 'selected' : '' }}>Married</option>
                                    <option value="widowed" {{ (old('civilstatus', $profile->civilstatus ?? '') == 'widowed') ? 'selected' : '' }}>Widowed</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="street"  class="form-label">Street</label>
                                <input type="text" name="street" id="street" class="form-control w-75" value="{{ old('street', $profile->street ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="barangay"  class="form-label">Barangay</label>
                                <input type="text" name="barangay" id="barangay" class="form-control w-75" value="{{ old('barangay', $profile->barangay ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="municipality"  class="form-label">Municipality</label>
                                <input type="text" name="municipality" id="municipality" class="form-control w-75" value="{{ old('municipality', $profile->municipality ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="province"  class="form-label">Province</label>
                                <input type="text" name="province" id="province" class="form-control w-75" value="{{ old('province', $profile->province ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="religion"  class="form-label">Religion</label>
                                <input type="text" name="religion" id="religion" class="form-control w-75" value="{{ old('religion', $profile->religion ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="contactnumber"  class="form-label">Contact Number</label>
                                <input type="text" name="contactnumber" id="contactnumber" class="form-control w-75" value="{{ old('contactnumber', $profile->contactnumber ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="email"  class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control w-75" value="{{ old('email', $profile->email ?? '') }}">
                            </div>
                        </div>


                        <!-- Employment status -->
                        <div id="section-employment-status">
                            <div class="mb-3">
                                <label class="form-label styled-label">Disability</label><br>
                                @php
                                    $userDisabilities = $profile && $profile->disabilities ? $profile->disabilities->pluck('id')->toArray() : [];
                                @endphp
                                @foreach($disabilities as $disability)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="disabilities[]" 
                                               id="disability_{{ $disability->id }}" value="{{ $disability->id }}" 
                                               {{ in_array($disability->id, $userDisabilities) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="disability_{{ $disability->id }}">{{ $disability->name }}</label>
                                    </div>
                                @endforeach
                                <div class="mt-2">
                                    <small class="text-muted">Select all disabilities that apply to you for appropriate workplace accommodations.</small>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label styled-label">4PS Benificiary?</label><br>
                                <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="is_4ps" id="is_4ps" value="yes" {{ (old('is_4ps', $profile->is_4ps ?? false)) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_4ps">Yes</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="employmentstatus" class="form-label">Employment Status</label>
                                <select name="employmentstatus" id="employmentstatus" class="form-control w-75">
                                    <option value="">Select</option>
                                    <option value="employed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'employed') ? 'selected' : '' }}>Employed</option>
                                    <option value="unemployed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'unemployed') ? 'selected' : '' }}>Unemployed</option>
                                </select>
                            </div>
                        </div>

                        <!-- Job Preferences -->
                        <div id="section-job-preferences">
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
                                                        <input type="text" name="job_preferences[{{ $index }}][preferred_job_title]" class="form-control w-75" 
                                                               value="{{ old('job_preferences.'.$index.'.preferred_job_title', $preference->preferred_job_title) }}" 
                                                               placeholder="e.g., Software Developer">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Job Classification</label>
                                                        <select name="job_preferences[{{ $index }}][preferred_classification]" class="form-control w-75">
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
                                                        <input type="number" name="job_preferences[{{ $index }}][min_salary]" class="form-control w-75" 
                                                               value="{{ old('job_preferences.'.$index.'.min_salary', $preference->min_salary) }}" 
                                                               step="0.01" placeholder="15000">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Max Salary (PHP)</label>
                                                        <input type="number" name="job_preferences[{{ $index }}][max_salary]" class="form-control w-75" 
                                                               value="{{ old('job_preferences.'.$index.'.max_salary', $preference->max_salary) }}" 
                                                               step="0.01" placeholder="25000">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Preferred Location</label>
                                                        <input type="text" name="job_preferences[{{ $index }}][preferred_location]" class="form-control w-75" 
                                                               value="{{ old('job_preferences.'.$index.'.preferred_location', $preference->preferred_location) }}" 
                                                               placeholder="e.g., Makati, Remote">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label">Employment Type</label>
                                                        <select name="job_preferences[{{ $index }}][preferred_employment_type]" class="form-control w-75">
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
                                                    <input type="number" name="job_preferences[0][min_salary]" class="form-control w-75" step="0.01" placeholder="15000">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="mb-3">
                                                    <label class="form-label">Max Salary (PHP)</label>
                                                    <input type="number" name="job_preferences[0][max_salary]" class="form-control w-75" step="0.01" placeholder="25000">
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
                        </div>

                        <!-- Educational background -->
                        <div id="section-educational-background">
                            <h3>Educational Background</h3>
                            <p class="text-muted">Please select your highest education level and provide details.</p>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="education_level_id" class="form-label">Highest Education Level</label>
                                        <select name="education_level_id" id="education_level_id" class="form-control">
                                            <option value="">Select Education Level</option>
                                            @foreach($educationLevels as $level)
                                                <option value="{{ $level->id }}" 
                                                        {{ old('education_level_id', $profile->education_level_id ?? '') == $level->id ? 'selected' : '' }}>
                                                    {{ $level->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="institution_name" class="form-label">Institution Name</label>
                                        <input type="text" name="institution_name" id="institution_name" 
                                               class="form-control" 
                                               value="{{ old('institution_name', $profile->institution_name ?? '') }}" 
                                               placeholder="Name of school/university">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="graduation_year" class="form-label">Graduation Year</label>
                                        <input type="number" name="graduation_year" id="graduation_year" 
                                               class="form-control" 
                                               value="{{ old('graduation_year', $profile->graduation_year ?? '') }}" 
                                               placeholder="e.g., 2020" min="1950" max="{{ date('Y') + 10 }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="gpa" class="form-label">GPA (Optional)</label>
                                        <input type="number" name="gpa" id="gpa" 
                                               class="form-control" step="0.01" min="1" max="4" 
                                               value="{{ old('gpa', $profile->gpa ?? '') }}" 
                                               placeholder="e.g., 3.75">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="degree_field" class="form-label">Field of Study</label>
                                        <input type="text" name="degree_field" id="degree_field" 
                                               class="form-control" 
                                               value="{{ old('degree_field', $profile->degree_field ?? '') }}" 
                                               placeholder="e.g., Computer Science">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Work experience -->
                        <div id="section-work-experience">
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
                        </div>

                        <!-- Skills -->
                        <div id="section-skills">
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
                                Please check all that apply and add any other skills not listed. Use common terms to help employers find you more easily.
                            </small>
                        </div>


                       
                      
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

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
    </script>
@endsection