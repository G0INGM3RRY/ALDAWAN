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
                        <div id="section-personal-information" class="form-step active">
                            <h4 class="mb-3">Personal Information</h4>
                            
                            <!-- Display job seeker type as readonly -->
                            <div class="mb-3">
                                <label class="form-label">Job Seeker Type</label>
                                <p class="text-muted">Informal Worker - This cannot be changed after registration</p>
                                <input type="hidden" name="job_seeker_type" value="informal">
                            </div>
                        
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $profile->first_name ?? '') }}" required>
                                        @error('first_name')<div class="text-danger">{{ $message }}</div>@enderror
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
                                        @error('last_name')<div class="text-danger">{{ $message }}</div>@enderror
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
                                        <label for="birthday" class="form-label">Date of Birth</label>
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
                                                <input type="radio" name="sex" id="informal_edit_male" value="male" class="form-check-input" {{ old('sex', $profile->sex ?? '') == 'male' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="informal_edit_male">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="sex" id="informal_edit_female" value="female" class="form-check-input" {{ old('sex', $profile->sex ?? '') == 'female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="informal_edit_female">Female</label>
                                            </div>
                                        </div>   
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="photo" class="form-label">Photo</label>
                                        <input type="file" name="photo" id="photo" class="form-control">
                                        <small class="form-text text-muted">Upload your profile photo (JPG, PNG, max 2MB)</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @if($profile && $profile->photo)
                                         <div class="mt-2">
                                             <img src="{{ asset('storage/' . $profile->photo) }}" alt="Current Photo" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                            <small class="text-muted d-block">Current: {{ $profile->photo }}</small>
                                         </div>
                                    @endif
                                </div>
                            </div>
                        
                            
                            <div class="d-flex">
                                <div class="mb-3">
                                    <label for="street" class="form-label">Street</label>
                                    <input type="text" name="street" id="street" class="form-control w-100" value="{{ old('street', $profile->street ?? '') }}">
                                </div>
                            
                                <div class="mb-3">
                                    <label for="barangay" class="form-label">Barangay</label>
                                    <input type="text" name="barangay" id="barangay" class="form-control w-100" value="{{ old('barangay', $profile->barangay ?? '') }}">
                                </div>
                            </div>
                            
                            <div class="d-flex">
                                <div class="mb-3">
                                    <label for="municipality" class="form-label">Municipality</label>
                                    <input type="text" name="municipality" id="municipality" class="form-control w-100" value="{{ old('municipality', $profile->municipality ?? '') }}">
                                </div>
                            
                                <div class="mb-3">
                                    <label for="province" class="form-label">Province</label>
                                    <input type="text" name="province" id="province" class="form-control w-100" value="{{ old('province', $profile->province ?? '') }}">
                                </div>
                            </div>
                            
                            <div class="d-flex">
                                <div class="mb-3">
                                    <label for="contactnumber" class="form-label">Contact Number</label>
                                    <input type="text" name="contactnumber" id="contactnumber" class="form-control w-100" value="{{ old('contactnumber', $profile->contactnumber ?? '') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="religion" class="form-label">Religion</label>
                                    <input type="text" name="religion" id="religion" class="form-control w-100">
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-center">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-muted">(Registered Email)</span></label>
                                    <input type="email" name="email" id="email" class="form-control w-100 bg-light" 
                                           value="{{ Auth::user()->email }}" readonly>
                                    <div class="form-text text-muted">
                                        <i class="fas fa-lock me-1"></i>This is your registered email. 
                                        <a href="{{ route('profile.edit') }}" class="text-primary">Change email in account settings</a>
                                    </div>
                                 </div>
                                 
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>   
                       

                        <!-- Basic Work Information -->
                        <div id="section-work-status" class="form-step">
                            <h4 class="mb-3 mt-4">Work Status & Benefits</h4>
                            
                            <div class="mb-3">
                                <label class="form-label">Disability (if any)</label>
                                <div class="mt-2">
                                    <small class="text-muted">Select all that apply for appropriate workplace accommodations.</small>
                                </div>
                                <br>
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
                                

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="mb-3">
                                            <label for="other_disabilities" class="form-label">Other disabilities (not listed above):</label>
                                            <input type="text" name="other_disabilities" id="other_disabilities" class="form-control" placeholder="Type other disabilities here, separated by commas">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">4PS Beneficiary?</label><br>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_4ps" id="is_4ps" value="yes" {{ ($profile->is_4ps ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_4ps">Yes</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employmentstatus" class="form-label">Current Employment Status</label>
                                        <select name="employmentstatus" id="employmentstatus" class="form-control">
                                            <option value="">Select</option>
                                            <option value="employed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'employed') ? 'selected' : '' }}>Currently Working</option>
                                            <option value="unemployed" {{ (old('employmentstatus', $profile->employmentstatus ?? '') == 'unemployed') ? 'selected' : '' }}>Looking for Work</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Prev</button>
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Basic Skills -->
                        <div id="section-skills" class="form-step">
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
                            
                            <small class="form-text text-muted mb-3">
                                <i class="fas fa-info-circle text-primary"></i> <strong>Smart Skills Display:</strong> We show the 20 most popular and relevant skills. Custom skills you add will be included in future selections based on usage.
                            </small>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="skills_other" class="form-label">Other Skills (separate by comma)</label>
                                        <textarea name="skills_other" id="skills_other" class="form-control" rows="3" placeholder="e.g., sewing, massage therapy, tutoring">{{ old('skills_other', '') }}</textarea>
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
    </style>

    <script>
        //step by step 
        let currentStep = 0;
        const steps = document.querySelectorAll(".form-step");

        function showStep(step){
            steps.forEach((s, i)=> {
                s.classList.remove("active");
                if(i === step){
                    s.classList.add("active");
                }
            });
        }

        function prevStep(){
            if(currentStep > 0){
                currentStep--;
                showStep(currentStep);
            }
        }

        function nextStep(){
            if(currentStep < steps.length-1){
                currentStep++;
                showStep(currentStep);
            }
        }
        showStep(currentStep);
    </script>
@endsection
