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
                                    <small class="text-muted">Select all that apply for appropriate workplace accommodations.</small>
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
                                    $userSkills = $profile && $profile->skills ? $profile->skills->pluck('id')->toArray() : [];
                                @endphp
                                <div class="row">
                                    @foreach($informalSkills as $skill)
                                        <div class="col-md-6 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="informal_skills[]" 
                                                       id="informal_skill_{{ $skill->id }}" value="{{ $skill->id }}" 
                                                       {{ in_array($skill->id, $userSkills) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="informal_skill_{{ $skill->id }}">{{ $skill->name }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="skills_other" class="form-label">Other Skills (separate by comma)</label>
                                <textarea name="skills_other" id="skills_other" class="form-control w-75" rows="3" placeholder="e.g., sewing, massage therapy, tutoring">{{ old('skills_other', '') }}</textarea>
                            </div>
                        </div>

                        <!-- Work Experience section removed - using structured work_experiences instead -->

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Update Profile</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
