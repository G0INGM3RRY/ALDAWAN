@extends('layouts.dashboard')

@section('content')
    <h1>Edit Your Profile - Informal Worker</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 text-center">Update Your Profile</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobseekers.informal.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                
                        <!-- Personal Information -->
                        <div id="section-personal-information">
                            <h4 class="mb-3">Personal Information</h4>
                            
                            <!-- Display job seeker type as readonly -->
                            <div class="mb-3">
                                <label class="form-label">Job Seeker Type</label>
                                <p class="text-muted">Informal Worker - This cannot be changed after registration</p>
                                <input type="hidden" name="job_seeker_type" value="informal">
                            </div>
                            
                            <div class="mb-3">
                                <label for="first_name" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control w-75" value="{{ old('first_name', $profile->first_name ?? '') }}" required>
                                @error('first_name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" name="middle_name" class="form-control w-75" value="{{ old('middle_name', $profile->middle_name ?? '') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="last_name" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control w-75" value="{{ old('last_name', $profile->last_name ?? '') }}" required>
                                @error('last_name')<div class="text-danger">{{ $message }}</div>@enderror
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
                                <label for="birthday" class="form-label">Date of Birth</label>
                                <input type="date" name="birthday" id="birthday" class="form-control w-75" value="{{ old('birthday', $profile->birthday ?? '') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="sex" class="form-label">Sex</label>
                                <select name="sex" class="form-control w-75">
                                    <option value="">Select</option>
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
                        </div>

                        <!-- Contact & Location Information -->
                        <div id="section-contact-location">
                            <h4 class="mb-3 mt-4">Contact & Location</h4>
                            
                            <div class="mb-3">
                                <label for="street" class="form-label">Street</label>
                                <input type="text" name="street" id="street" class="form-control w-75" value="{{ old('street', $profile->street ?? '') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="barangay" class="form-label">Barangay</label>
                                <input type="text" name="barangay" id="barangay" class="form-control w-75" value="{{ old('barangay', $profile->barangay ?? '') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="municipality" class="form-label">Municipality</label>
                                <input type="text" name="municipality" id="municipality" class="form-control w-75" value="{{ old('municipality', $profile->municipality ?? '') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="province" class="form-label">Province</label>
                                <input type="text" name="province" id="province" class="form-control w-75" value="{{ old('province', $profile->province ?? '') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="contactnumber" class="form-label">Contact Number</label>
                                <input type="text" name="contactnumber" id="contactnumber" class="form-control w-75" value="{{ old('contactnumber', $profile->contactnumber ?? '') }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control w-75" value="{{ old('email', $profile->email ?? '') }}">
                            </div>
                        </div>

                        <!-- Basic Work Information -->
                        <div id="section-work-status">
                            <h4 class="mb-3 mt-4">Work Status & Benefits</h4>
                            
                            <div class="mb-3">
                                <label class="form-label">Disability (if any)</label><br>
                                @php
                                    $disabilities = is_string($profile->disability ?? '') ? json_decode($profile->disability ?? '[]', true) : ($profile->disability ?? []);
                                    if (!is_array($disabilities)) $disabilities = [];
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
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_physical" value="physical" {{ in_array('physical', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_physical">Physical</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="disability[]" id="disability_others" value="others" {{ in_array('others', $disabilities) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="disability_others">Others</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">4PS Beneficiary?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_4ps" id="is_4ps" value="yes" {{ ($profile->is_4ps ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_4ps">Yes</label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="employmentstatus" class="form-label">Current Employment Status</label>
                                <select name="employmentstatus" id="employmentstatus" class="form-control w-75">
                                    <option value="">Select</option>
                                    <option value="employed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'employed') ? 'selected' : '' }}>Currently Working</option>
                                    <option value="unemployed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'unemployed') ? 'selected' : '' }}>Looking for Work</option>
                                </select>
                            </div>
                        </div>

                        <!-- Basic Skills -->
                        <div id="section-skills">
                            <h4 class="mb-3 mt-4">Skills & Experience</h4>
                            
                            <div class="mb-3">
                                <label class="form-label">Basic Skills (Select all that apply)</label><br>
                                @php
                                    $skills = is_string($profile->skills ?? '') ? json_decode($profile->skills ?? '[]', true) : ($profile->skills ?? []);
                                    if (!is_array($skills)) $skills = [];
                                @endphp
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_cleaning" value="cleaning" {{ in_array('cleaning', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_cleaning">Cleaning Services</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_cooking" value="cooking" {{ in_array('cooking', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_cooking">Cooking</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_gardening" value="gardening" {{ in_array('gardening', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_gardening">Gardening</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_construction" value="construction" {{ in_array('construction', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_construction">Construction Work</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_delivery" value="delivery" {{ in_array('delivery', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_delivery">Delivery Services</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_driving" value="driving" {{ in_array('driving', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_driving">Driving</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_repair" value="repair" {{ in_array('repair', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_repair">Basic Repairs</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_selling" value="selling" {{ in_array('selling', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_selling">Selling/Retail</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_babysitting" value="babysitting" {{ in_array('babysitting', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_babysitting">Childcare</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="skills[]" id="skill_elderly_care" value="elderly_care" {{ in_array('elderly_care', $skills) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_elderly_care">Elderly Care</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="skills_other" class="form-label">Other Skills (separate by comma)</label>
                                <textarea name="skills_other" id="skills_other" class="form-control w-75" rows="3" placeholder="e.g., sewing, massage therapy, tutoring">{{ old('skills_other', $profile->skills_other ?? '') }}</textarea>
                            </div>
                        </div>

                        <!-- Simple Work Experience -->
                        <div id="section-work-experience">
                            <h4 class="mb-3 mt-4">Work Experience (Optional)</h4>
                            
                            <div class="mb-3">
                                <label for="work_experience" class="form-label">Previous Work Experience</label>
                                <textarea name="work_experience" id="work_experience" class="form-control w-75" rows="4" placeholder="Briefly describe your previous work experience, including what type of work you did and for how long.">{{ old('work_experience', is_string($profile->work_experience ?? '') ? $profile->work_experience : (is_array($profile->work_experience ?? []) ? implode(', ', $profile->work_experience) : '')) }}</textarea>
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
