@extends('layouts.dashboard')

@section('content')
    <h1>Complete Your Profile - Informal Worker</h1>
    
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
                    <h3 class="mb-0 text-center">Complete Your Profile Information</h3>
                    <!-- Progress Steps -->
                    <div class="progress mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 33%" id="progress-bar"></div>
                    </div>
                    <div class="step-indicators d-flex justify-content-between mt-2">
                        <span class="step-indicator active" id="step-1">1. Personal Info</span>
                        <span class="step-indicator" id="step-2">2. Work Preferences</span>
                        <span class="step-indicator" id="step-3">3. Document Verification</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobseeker.informal.complete') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                   
                        <!-- Section 1: Personal Information -->
                        <div id="section-personal-information" class="form-step active">
                            <h4 class="mb-4">Personal Information</h4>
                            <p class="text-muted mb-4"><span class="text-danger">*</span> Required field</p>
                            
                            <!-- Display job seeker type as readonly -->
                            <div class="mb-3">
                                <label class="form-label">Job Seeker Type</label>
                                <p class="text-muted">Informal Worker - Selected during registration</p>
                                <input type="hidden" name="job_seeker_type" value="informal">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control" required>
                                        @error('first_name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" name="middle_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" class="form-control" required>
                                        @error('last_name')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="suffix" class="form-label">Suffix</label>
                                        <select name="suffix" class="form-control">
                                            <option value="">None</option>
                                            <option value="Jr.">Jr.</option>
                                            <option value="Sr.">Sr.</option>
                                            <option value="III">III</option>
                                            <option value="IV">IV</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="birthday" class="form-label">Date of Birth</label>
                                        <input type="date" name="birthday" id="birthday" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="civilstatus" class="form-label">Civil Status</label>
                                        <select name="civilstatus" class="form-control">
                                            <option value="">Select</option>
                                            <option value="single">Single</option>
                                            <option value="married">Married</option>
                                            <option value="widowed">Widowed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="sex" class="form-label">Sex</label>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="sex" id="informal_complete_male" value="male" class="form-check-input" {{ old('sex') == 'male' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="informal_complete_male">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="sex" id="informal_complete_female" value="female" class="form-check-input" {{ old('sex') == 'female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="informal_complete_female">Female</label>
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
                                    @if(isset($profile) && $profile->photo)
                                        <div class="mt-2">
                                            <img src="{{ asset('storage/' . $profile->photo) }}" alt="Current Photo" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                                            <small class="text-muted d-block">Current: {{ $profile->photo }}</small>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">Address Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="street" class="form-label">Street</label>
                                        <input type="text" name="street" id="street" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barangay" class="form-label">Barangay</label>
                                        <input type="text" name="barangay" id="barangay" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="municipality" class="form-label">Municipality</label>
                                        <input type="text" name="municipality" id="municipality" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="province" class="form-label">Province</label>
                                        <input type="text" name="province" id="province" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">Contact Information</h5>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="contactnumber" class="form-label">Contact Number</label>
                                        <input type="text" name="contactnumber" id="contactnumber" class="form-control">
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
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="religion" class="form-label">Religion</label>
                                        <input type="text" name="religion" id="religion" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Section 2: Skills and Work Preferences -->
                        <div id="section-work-preferences" class="form-step">
                            <h4 class="mb-4">Skills & Work Preferences</h4>
                            
                            <h5 class="mb-3">Your Skills</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Informal Skills (Check all that apply)</label>
                                        <div class="row">
                                            @if(isset($informalSkills))
                                                @foreach($informalSkills as $skill)
                                                    <div class="col-md-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" 
                                                                   name="informal_skills[]" 
                                                                   id="informal_skill_{{ $skill->id }}" 
                                                                   value="{{ $skill->id }}">
                                                            <label class="form-check-label" for="informal_skill_{{ $skill->id }}">
                                                                {{ $skill->name }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="informal_skills[]" id="cooking" value="cooking">
                                                        <label class="form-check-label" for="cooking">Cooking</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="informal_skills[]" id="cleaning" value="cleaning">
                                                        <label class="form-check-label" for="cleaning">House Cleaning</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="informal_skills[]" id="gardening" value="gardening">
                                                        <label class="form-check-label" for="gardening">Gardening</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="informal_skills[]" id="childcare" value="childcare">
                                                        <label class="form-check-label" for="childcare">Child Care</label>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="skills_other" class="form-label">Other Skills (separate by comma)</label>
                                        <textarea name="skills_other" id="skills_other" class="form-control" rows="3" 
                                                  placeholder="e.g., sewing, massage therapy, tutoring"></textarea>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">Work Preferences</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="preferred_work_type" class="form-label">Preferred Work Type</label>
                                        <select name="preferred_work_type" class="form-control">
                                            <option value="">Select preferred work type</option>
                                            <option value="household_services">Household Services</option>
                                            <option value="construction_labor">Construction/Labor</option>
                                            <option value="food_service">Food Service</option>
                                            <option value="retail_sales">Retail/Sales</option>
                                            <option value="delivery_transport">Delivery/Transport</option>
                                            <option value="care_services">Care Services</option>
                                            <option value="repair_maintenance">Repair/Maintenance</option>
                                            <option value="agriculture">Agriculture</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="preferred_schedule" class="form-label">Preferred Work Schedule</label>
                                        <select name="preferred_schedule" class="form-control">
                                            <option value="">Select schedule preference</option>
                                            <option value="full_time">Full Time (8 hours/day)</option>
                                            <option value="part_time">Part Time (4-6 hours/day)</option>
                                            <option value="flexible">Flexible Hours</option>
                                            <option value="weekends_only">Weekends Only</option>
                                            <option value="project_based">Project-based</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="preferred_salary_range" class="form-label">Expected Daily Rate (PHP)</label>
                                        <select name="preferred_salary_range" class="form-control">
                                            <option value="">Select expected daily rate</option>
                                            <option value="200-400">₱200 - ₱400</option>
                                            <option value="400-600">₱400 - ₱600</option>
                                            <option value="600-800">₱600 - ₱800</option>
                                            <option value="800-1000">₱800 - ₱1,000</option>
                                            <option value="1000+">₱1,000 and above</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="preferred_location" class="form-label">Preferred Work Location</label>
                                        <input type="text" name="preferred_location" id="preferred_location" 
                                               class="form-control" placeholder="e.g., Makati, Quezon City">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Previous</button>
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Section 3: Document Verification (Optional) -->
                        <div id="section-document-verification" class="form-step">
                            <h4 class="mb-4 text-primary">
                                <i class="fas fa-file-upload me-2"></i>Document Verification (Optional)
                            </h4>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Optional Step:</strong> Upload your verification documents now to get verified faster, 
                                or you can submit them later from your dashboard.
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-id-card me-1"></i>Government-issued ID 
                                            <span class="text-muted">(Required for verification)</span>
                                        </label>
                                        <input type="file" name="basic_id" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="form-text">
                                            Upload a clear photo of any government ID (Driver's License, UMID, SSS, Voter's ID, etc.)
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-home me-1"></i>Barangay Clearance 
                                            <span class="text-muted">(Required for verification)</span>
                                        </label>
                                        <input type="file" name="barangay_clearance" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="form-text">
                                            Upload your barangay clearance or certificate of good moral character
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-heart-pulse me-1"></i>Health Certificate 
                                            <span class="text-success">(Optional - for household/food handling jobs)</span>
                                        </label>
                                        <input type="file" name="health_certificate" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="form-text">
                                            Upload a health certificate if you plan to work in household services or food handling
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="alert alert-light">
                                        <h6 class="mb-2"><i class="fas fa-lightbulb me-1"></i>Why these documents?</h6>
                                        <ul class="small mb-0">
                                            <li><strong>ID:</strong> Verify your identity</li>
                                            <li><strong>Barangay Clearance:</strong> Character reference from your community</li>
                                            <li><strong>Health Certificate:</strong> Safety for household and food-related work</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Note:</strong> All documents will be reviewed by our admin team. 
                                You can complete your profile now and submit documents later if needed.
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Previous</button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check me-2"></i>Complete Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .step-indicator {
            padding: 8px 12px;
            border-radius: 20px;
            background-color: #f8f9fa;
            color: #6c757d;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .step-indicator.active {
            background-color: #007bff;
            color: white;
        }
        .step-indicator.completed {
            background-color: #28a745;
            color: white;
        }
    </style>

    <script>
        let currentStep = 0;
        const steps = document.querySelectorAll(".form-step");
        const totalSteps = steps.length;

        function showStep(step) {
            steps.forEach((s, i) => {
                s.classList.remove("active");
                if (i === step) {
                    s.classList.add("active");
                }
            });
            
            // Update progress bar
            const progressPercentage = ((step + 1) / totalSteps) * 100;
            document.getElementById('progress-bar').style.width = progressPercentage + '%';
            
            // Update step indicators
            document.querySelectorAll('.step-indicator').forEach((indicator, i) => {
                indicator.classList.remove('active', 'completed');
                if (i === step) {
                    indicator.classList.add('active');
                } else if (i < step) {
                    indicator.classList.add('completed');
                }
            });
        }

        function prevStep() {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        }

        function nextStep() {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        }

        // Initialize
        showStep(currentStep);
    </script>
@endsection