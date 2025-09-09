
@extends('layouts.dashboard')

@section('content')
    <h1>Manage your personal profile</h1>
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
                                    $disabilities = $profile && $profile->disability ? json_decode($profile->disability, true) : [];
                                    $disabilities = is_array($disabilities) ? $disabilities : [];
                                @endphp
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_visual" value="visual" {{ in_array('visual', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_visual">Visual</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_hearing" value="hearing" {{ in_array('hearing', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_hearing">Hearing</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_speech" value="speech" {{ in_array('speech', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_speech">Speech</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_physical" value="physical" {{ in_array('physical', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_physical">Physical</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_mental" value="mental" {{ in_array('mental', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_mental">Mental</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_others" value="others">
                                    <label class="form-check-label" for="disability_others">Others</label>
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
                            <p class="text-muted">Please fill in your educational attainment from elementary up to the highest level attained.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle education-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Level</th>
                                            <th>School Attended</th>
                                            <th>Year Graduated</th>
                                            <th>Honors/Remarks</th>
                                            <th>Highest Level/Units Earned</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Elementary</td>
                                            <td><input type="text" name="education[elementary][school]" class="form-control w-75" placeholder="School name"></td>
                                            <td><input type="text" name="education[elementary][year]" class="form-control w-75" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[elementary][honors]" class="form-control w-75" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[elementary][units]" class="form-control w-75" placeholder="N/A"></td>
                                        </tr>
                                        <tr>
                                            <td>High School</td>
                                            <td><input type="text" name="education[high_school][school]" class="form-control w-75" placeholder="School name"></td>
                                            <td><input type="text" name="education[high_school][year]" class="form-control w-75" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[high_school][honors]" class="form-control w-75" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[high_school][units]" class="form-control w-75" placeholder="N/A"></td>
                                        </tr>
                                        <tr>
                                            <td>College</td>
                                            <td><input type="text" name="education[college][school]" class="form-control w-75" placeholder="School name"></td>
                                            <td><input type="text" name="education[college][year]" class="form-control w-75" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[college][honors]" class="form-control w-75" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[college][units]" class="form-control w-75" placeholder="If undergraduate, highest year/units"></td>
                                        </tr>
                                        <tr>
                                            <td>Vocational</td>
                                            <td><input type="text" name="education[vocational][school]" class="form-control w-75" placeholder="School name"></td>
                                            <td><input type="text" name="education[vocational][year]" class="form-control w-75" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[vocational][honors]" class="form-control w-75" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[vocational][units]" class="form-control w-75" placeholder="Course/Units"></td>
                                        </tr>
                                        <tr>
                                            <td>Graduate Studies</td>
                                            <td><input type="text" name="education[graduate][school]" class="form-control w-75" placeholder="School name"></td>
                                            <td><input type="text" name="education[graduate][year]" class="form-control w-75" placeholder="Year graduated"></td>
                                            <td><input type="text" name="education[graduate][honors]" class="form-control w-75" placeholder="Honors/Remarks"></td>
                                            <td><input type="text" name="education[graduate][units]" class="form-control w-75" placeholder="Course/Units"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Work experience -->
                        <div id="section-work-experience">
                            <h3>Work Experience</h3>
                            <p class="text-muted">List your previous work experiences. Leave blank if not applicable.</p>
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle experience-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Employer Name</th>
                                            <th>Address</th>
                                            <th>Position Held</th>
                                            <th>Date From</th>
                                            <th>Date To</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="text" name="work[0][employer]" class="form-control w-75" placeholder="Employer name"></td>
                                            <td><input type="text" name="work[0][address]" class="form-control w-75" placeholder="Address"></td>
                                            <td><input type="text" name="work[0][position]" class="form-control w-75" placeholder="Position held"></td>
                                            <td><input type="date" name="work[0][date_from]" class="form-control w-75"></td>
                                            <td><input type="date" name="work[0][date_to]" class="form-control w-75"></td>
                                            <td>
                                                <select name="work[0][status]" class="form-control w-75">
                                                    <option value="">Select</option>
                                                    <option value="permanent">Permanent</option>
                                                    <option value="contractual">Contractual</option>
                                                    <option value="probationary">Probationary</option>
                                                    <option value="part_time">Part-time</option>
                                                    <option value="casual">Casual</option>
                                                    <option value="project_based">Project-based</option>
                                                    <option value="seasonal">Seasonal</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="work[1][employer]" class="form-control w-75" placeholder="Employer name"></td>
                                            <td><input type="text" name="work[1][address]" class="form-control w-75" placeholder="Address"></td>
                                            <td><input type="text" name="work[1][position]" class="form-control w-75" placeholder="Position held"></td>
                                            <td><input type="date" name="work[1][date_from]" class="form-control w-75"></td>
                                            <td><input type="date" name="work[1][date_to]" class="form-control w-75"></td>
                                            <td>
                                                <select name="work[1][status]" class="form-control w-75">
                                                    <option value="">Select</option>
                                                    <option value="permanent">Permanent</option>
                                                    <option value="contractual">Contractual</option>
                                                    <option value="probationary">Probationary</option>
                                                    <option value="part_time">Part-time</option>
                                                    <option value="casual">Casual</option>
                                                    <option value="project_based">Project-based</option>
                                                    <option value="seasonal">Seasonal</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><input type="text" name="work[2][employer]" class="form-control w-75" placeholder="Employer name"></td>
                                            <td><input type="text" name="work[2][address]" class="form-control w-75" placeholder="Address"></td>
                                            <td><input type="text" name="work[2][position]" class="form-control w-75" placeholder="Position held"></td>
                                            <td><input type="date" name="work[2][date_from]" class="form-control w-75"></td>
                                            <td><input type="date" name="work[2][date_to]" class="form-control w-75"></td>
                                            <td>
                                                <select name="work[2][status]" class="form-control w-75">
                                                    <option value="">Select</option>
                                                    <option value="permanent">Permanent</option>
                                                    <option value="contractual">Contractual</option>
                                                    <option value="probationary">Probationary</option>
                                                    <option value="part_time">Part-time</option>
                                                    <option value="casual">Casual</option>
                                                    <option value="project_based">Project-based</option>
                                                    <option value="seasonal">Seasonal</option>
                                                    <option value="others">Others</option>
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <small class="text-muted">Add more rows if needed.</small>
                            </div>
                        </div>

                        <!-- Skills -->
                        <div id="section-skills">
                            <h3>Skills</h3>
                            <label class="form-label">Select your skills</label>
                            <div class="mb-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_gardening" value="gardening">
                                    <label class="form-check-label" for="skill_gardening">Gardening</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_carpentry" value="carpentry">
                                    <label class="form-check-label" for="skill_carpentry">Carpentry</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_computer_repair" value="computer repair">
                                    <label class="form-check-label" for="skill_computer_repair">Computer Repair</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_sewing" value="sewing">
                                    <label class="form-check-label" for="skill_sewing">Sewing</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_driving" value="driving">
                                    <label class="form-check-label" for="skill_driving">Driving</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_cooking" value="cooking">
                                    <label class="form-check-label" for="skill_cooking">Cooking</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="skills[]" id="skill_cleaning" value="cleaning">
                                    <label class="form-check-label" for="skill_cleaning">Cleaning</label>
                                </div>
                                <!-- Add more common skills as needed -->
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
    </script>
@endsection