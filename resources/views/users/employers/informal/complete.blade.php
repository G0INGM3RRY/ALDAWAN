@extends('layouts.dashboard')

@section('content')
    <h1>Complete your Profile</h1>
    
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
                    <h3 class="mb-0 text-center">Complete your Profile</h3>
                    <!-- Progress Steps -->
                    <div class="progress mt-3">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 33%" id="progress-bar"></div>
                    </div>
                    <div class="step-indicators d-flex justify-content-between mt-2">
                        <span class="step-indicator active" id="step-1">1. Basic Info</span>
                        <span class="step-indicator" id="step-2">2. Contact & Location</span>
                        <span class="step-indicator" id="step-3">3. Document Verification</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('employers.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                   
                        <!-- Section 1: Basic Information -->
                        <div id="section-basic-information" class="form-step active">
                            <h4 class="mb-4">Basic Information</h4>
                            <p class="text-muted mb-4"><span class="text-danger">*</span> Required field</p>
                            
                            <div class="mb-3">
                                <label for="employer_type" class="form-label">Employer Type <span class="text-danger">*</span></label>
                                <p class="text-muted">Informal Employer - Selected during registration</p>
                                <input type="hidden" name="employer_type" value="informal">
                            </div>
                            
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Name / Household Name <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" class="form-control" 
                                       value="{{ old('company_name', Auth::user()->employerProfile->company_name ?? '') }}" required>
                                <div class="form-text">
                                    <small>Enter your name or household name (e.g., "Smith Family", "Cruz Household")</small>
                                </div>
                                @error('company_name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                           
                            <div class="mb-3">
                                <label for="company_logo" class="form-label">Profile Photo</label>
                                <input type="file" name="company_logo" id="company_logo" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Upload a profile photo (JPG, PNG, max 2MB)</small>
                                @php $profile = Auth::user()->employerProfile; @endphp
                                @if($profile && $profile->company_logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $profile->company_logo) }}" alt="Profile Photo" class="img-thumbnail" style="max-width: 200px;">
                                        <small class="text-muted d-block">Current photo</small>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="company_description" class="form-label">About You / Services Needed</label>
                                <textarea name="company_description" id="company_description" class="form-control" rows="4" 
                                          placeholder="Tell us about yourself and what kind of help you need...">{{ old('company_description') }}</textarea>
                                <div class="form-text">
                                    <small><strong>Examples:</strong><br>
                                    • "Family of 4 looking for a reliable house cleaner twice a week"<br>
                                    • "Elderly couple needs a part-time caregiver for daily assistance"<br>
                                    • "Need a driver for school pickup and grocery runs"</small>
                                </div>
                                @error('company_description')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" onclick="nextStep()" class="btn btn-success">Next</button>
                            </div>
                        </div>

                        <!-- Section 2: Contact & Location -->
                        <div id="section-contact-location" class="form-step">
                            <h4 class="mb-4">Contact & Location Information</h4>
                            
                            <h5 class="mt-4 mb-3">Home Address</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="street" class="form-label">Street</label>
                                        <input type="text" name="street" id="street" class="form-control" 
                                               value="{{ old('street', $profile->street ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="barangay" class="form-label">Barangay</label>
                                        <input type="text" name="barangay" id="barangay" class="form-control" 
                                               value="{{ old('barangay', $profile->barangay ?? '') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="municipality" class="form-label">Municipality</label>
                                        <input type="text" name="municipality" id="municipality" class="form-control" 
                                               value="{{ old('municipality', $profile->municipality ?? '') }}">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="province" class="form-label">Province</label>
                                        <input type="text" name="province" id="province" class="form-control" 
                                               value="{{ old('province', $profile->province ?? '') }}">
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4 mb-3">Contact Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="contactnumber" class="form-label">Contact Number</label>
                                        <input type="text" name="contactnumber" id="contactnumber" class="form-control" 
                                               placeholder="+63 XXX XXX XXXX">
                                    </div>
                                </div>
                                <div class="col-md-6">
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

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Previous</button>
                                <button type="button" onclick="nextStep()" class="btn btn-success">Next</button>
                            </div>
                        </div>

                        <!-- Section 3: Document Verification (Optional) -->
                        <div id="section-document-verification" class="form-step">
                            <h4 class="mb-4 text-success">
                                <i class="fas fa-file-upload me-2"></i>Document Verification (Optional)
                            </h4>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                <strong>Optional Step:</strong> Upload verification documents to build trust with job seekers. 
                                This helps workers feel more comfortable applying to your household.
                            </div>

                            <div class="mb-3">
                                <label for="verification_document" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>Valid ID
                                    <span class="text-success">(Optional)</span>
                                </label>
                                <input type="file" name="verification_document" id="verification_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="form-text">
                                    Upload a government-issued ID for verification (Driver's License, UMID, SSS, etc.) - PDF, JPG, PNG, max 5MB
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="verification_notes" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>Additional Information
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <textarea name="verification_notes" id="verification_notes" class="form-control" rows="3" 
                                          placeholder="Any additional information about your household, work schedule, or specific requirements..."></textarea>
                            </div>

                            <div class="alert alert-success">
                                <i class="fas fa-shield-alt me-2"></i>
                                <strong>Why verify?</strong> Verified households are more likely to receive applications from qualified workers.
                                Your documents will be reviewed by our admin team and kept confidential.
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
            background-color: #28a745;
            color: white;
        }
        .step-indicator.completed {
            background-color: #198754;
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
