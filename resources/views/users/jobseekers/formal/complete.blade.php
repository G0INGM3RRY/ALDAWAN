@extends('layouts.dashboard')

@section('content')
    <h1>Complete your personal profile</h1>
    
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
                    <h3 class="mb-0 text-center">Complete your personal profile</h3>
                    <!-- Progress Steps -->
                    <div class="progress mt-3">
                        <div class="progress-bar" role="progressbar" style="width: 25%" id="progress-bar"></div>
                    </div>
                    <div class="step-indicators d-flex justify-content-between mt-2">
                        <span class="step-indicator active" id="step-1">1. Personal Info</span>
                        <span class="step-indicator" id="step-2">2. Employment Status</span>
                        <span class="step-indicator" id="step-3">3. Job Preferences</span>
                        <span class="step-indicator" id="step-4">4. Document Verification</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('jobseeker.complete') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                   
                        <!-- Section 1: Personal Information -->
                        <div id="section-personal-information" class="form-step active">
                            <h4 class="mb-4">Personal Information</h4>
                            <p class="text-muted mb-4"><span class="text-danger">*</span> Required field</p>
                            
                            <div class="mb-3">
                                <label class="form-label">Job Seeker Type</label>
                                <p class="text-muted">Formal Worker - Selected during registration</p>
                                <input type="hidden" name="job_seeker_type" value="formal">
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" class="form-control" required>
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
                                        <label for="birthday" class="form-label">Date of birth</label>
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
                                                <input type="radio" name="sex" id="formal_complete_male" value="male" class="form-check-input" {{ old('sex') == 'male' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="formal_complete_male">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input type="radio" name="sex" id="formal_complete_female" value="female" class="form-check-input" {{ old('sex') == 'female' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="formal_complete_female">Female</label>
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

                        <!-- Section 2: Employment Status -->
                        <div id="section-employment-status" class="form-step">
                            <h4 class="mb-4">Employment Status & Information</h4>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Disability (Check all that apply)</label><br>
                                        @if(isset($disabilities))
                                            @foreach($disabilities as $disability)
                                                <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" name="disabilities[]" 
                                                           id="disability_{{ $disability->id }}" value="{{ $disability->id }}">
                                                    <label class="form-check-label" for="disability_{{ $disability->id }}">{{ $disability->name }}</label>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" name="disabilities[]" 
                                                       id="disability_none" value="none">
                                                <label class="form-check-label" for="disability_none">None</label>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">4PS Beneficiary?</label><br>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_4ps" id="is_4ps_yes" value="yes">
                                            <label class="form-check-label" for="is_4ps_yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="is_4ps" id="is_4ps_no" value="no">
                                            <label class="form-check-label" for="is_4ps_no">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="employmentstatus" class="form-label">Employment Status</label>
                                        <select name="employmentstatus" id="employmentstatus" class="form-control">
                                            <option value="">Select</option>
                                            <option value="employed">Employed</option>
                                            <option value="unemployed">Unemployed</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Previous</button>
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Section 3: Job Preferences -->
                        <div id="section-job-preferences" class="form-step">
                            <h4 class="mb-4">Job Preferences</h4>
                            <p class="text-muted">Specify your preferred job types and requirements. You can add multiple preferences.</p>
                            
                            <div id="job-preferences-container">
                                <div class="job-preference-item border p-3 mb-3 rounded">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Preferred Job Title</label>
                                                <input type="text" name="job_preferences[0][preferred_job_title]" class="form-control" placeholder="e.g., Software Developer">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Job Classification</label>
                                                <select name="job_preferences[0][job_classification_id]" class="form-control">
                                                    <option value="">Select Classification</option>
                                                    @if(isset($jobClassifications))
                                                        @foreach($jobClassifications as $classification)
                                                            <option value="{{ $classification->id }}">{{ $classification->name }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Min Salary (PHP)</label>
                                                <input type="number" name="job_preferences[0][min_salary]" class="form-control" step="0.01" placeholder="15000">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Max Salary (PHP)</label>
                                                <input type="number" name="job_preferences[0][max_salary]" class="form-control" step="0.01" placeholder="25000">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Preferred Location</label>
                                                <input type="text" name="job_preferences[0][preferred_location]" class="form-control" placeholder="e.g., Makati, Remote">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">Employment Type</label>
                                                <select name="job_preferences[0][preferred_employment_type]" class="form-control">
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
                                            <button type="button" class="btn btn-outline-primary" onclick="addJobPreference()">Add Another Preference</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Previous</button>
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Section 4: Document Verification (Optional) -->
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
                                        <input type="file" name="government_id" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="form-text">
                                            Upload a clear photo of your government ID (Driver's License, UMID, SSS, etc.)
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-graduation-cap me-1"></i>Educational Certificate/Diploma 
                                            <span class="text-muted">(Required for verification)</span>
                                        </label>
                                        <input type="file" name="educational_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="form-text">
                                            Upload your highest educational attainment certificate or diploma
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-shield-alt me-1"></i>NBI Clearance 
                                            <span class="text-muted">(Required for verification)</span>
                                        </label>
                                        <input type="file" name="nbi_clearance" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="form-text">
                                            Upload a valid NBI clearance for background verification
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">
                                            <i class="fas fa-certificate me-1"></i>Professional Skills Certificate 
                                            <span class="text-success">(Optional)</span>
                                        </label>
                                        <input type="file" name="skills_certificate" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                        <div class="form-text">
                                            Upload any professional certification or skills certificate you have
                                        </div>
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

        // Add job preference functionality
        let preferenceCount = 1;

        function addJobPreference() {
            const container = document.getElementById('job-preferences-container');
            const newPreference = `
                <div class="job-preference-item border p-3 mb-3 rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Preferred Job Title</label>
                                <input type="text" name="job_preferences[${preferenceCount}][preferred_job_title]" class="form-control" placeholder="e.g., Software Developer">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Job Classification</label>
                                <select name="job_preferences[${preferenceCount}][job_classification_id]" class="form-control">
                                    <option value="">Select Classification</option>
                                    @if(isset($jobClassifications))
                                        @foreach($jobClassifications as $classification)
                                            <option value="{{ $classification->id }}">{{ $classification->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Min Salary (PHP)</label>
                                <input type="number" name="job_preferences[${preferenceCount}][min_salary]" class="form-control" step="0.01" placeholder="15000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Max Salary (PHP)</label>
                                <input type="number" name="job_preferences[${preferenceCount}][max_salary]" class="form-control" step="0.01" placeholder="25000">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label">Preferred Location</label>
                                <input type="text" name="job_preferences[${preferenceCount}][preferred_location]" class="form-control" placeholder="e.g., Makati, Remote">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Employment Type</label>
                                <select name="job_preferences[${preferenceCount}][preferred_employment_type]" class="form-control">
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
            
            container.insertAdjacentHTML('beforeend', newPreference);
            preferenceCount++;
            
            // Add event listener to remove button
            const removeButtons = document.querySelectorAll('.remove-preference');
            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    this.closest('.job-preference-item').remove();
                });
            });
        }

        // Initialize
        showStep(currentStep);
    </script>
@endsection