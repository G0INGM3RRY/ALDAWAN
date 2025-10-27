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

                            <!-- Verification Documents Section -->
                            <div class="mt-5">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-file-upload me-2"></i>Document Verification
                                    @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->informalVerification)
                                        <span class="badge bg-{{ auth()->user()->jobseekerProfile->informalVerification->status === 'approved' ? 'success' : 
                                                                 (auth()->user()->jobseekerProfile->informalVerification->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst(auth()->user()->jobseekerProfile->informalVerification->status) }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">Not Submitted</span>
                                    @endif
                                </h5>
                                
                                @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->informalVerification)
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        <strong>Verification Status:</strong> 
                                        @if(auth()->user()->jobseekerProfile->informalVerification->status === 'approved')
                                            Your documents have been verified and approved.
                                        @elseif(auth()->user()->jobseekerProfile->informalVerification->status === 'rejected')
                                            Your documents were rejected. Please upload new documents.
                                            @if(auth()->user()->jobseekerProfile->informalVerification->rejection_reason)
                                                <br><strong>Reason:</strong> {{ auth()->user()->jobseekerProfile->informalVerification->rejection_reason }}
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
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" name="government_id" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="form-text">
                                                Upload a clear photo of your government ID
                                            </div>
                                            @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->informalVerification && auth()->user()->jobseekerProfile->informalVerification->government_id_path)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Current file uploaded
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-home me-1"></i>Barangay Clearance 
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="file" name="barangay_clearance" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="form-text">
                                                Upload your barangay clearance for community verification
                                            </div>
                                            @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->informalVerification && auth()->user()->jobseekerProfile->informalVerification->barangay_clearance_path)
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
                                                <i class="fas fa-heartbeat me-1"></i>Health Certificate 
                                                <span class="text-muted">(Optional - Required for food/health jobs)</span>
                                            </label>
                                            <input type="file" name="health_certificate" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="form-text">
                                                Upload medical certificate for food service, caregiving, or health-related jobs
                                            </div>
                                            @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->informalVerification && auth()->user()->jobseekerProfile->informalVerification->health_certificate_path)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Current file uploaded
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">
                                                <i class="fas fa-star me-1"></i>Character Reference Letter 
                                                <span class="text-muted">(Optional)</span>
                                            </label>
                                            <input type="file" name="character_reference" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                            <div class="form-text">
                                                Reference letter from previous employer, neighbor, or community leader
                                            </div>
                                            @if(auth()->user()->jobseekerProfile && auth()->user()->jobseekerProfile->informalVerification && auth()->user()->jobseekerProfile->informalVerification->character_reference_path)
                                                <small class="text-success">
                                                    <i class="fas fa-check-circle me-1"></i>Current file uploaded
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-light">
                                    <h6 class="mb-2"><i class="fas fa-lightbulb me-1"></i>Verification Benefits:</h6>
                                    <ul class="small mb-0">
                                        <li>✅ <strong>Verified badge</strong> on your profile</li>
                                        <li>✅ <strong>Increased trust</strong> from employers and clients</li>
                                        <li>✅ <strong>Better job matches</strong> based on your location</li>
                                        <li>✅ <strong>Higher response rates</strong> for applications</li>
                                    </ul>
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
