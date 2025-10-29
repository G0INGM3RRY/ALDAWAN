@extends('layouts.dashboard')

@section('content')
    <h1>Edit your Company Profile</h1>
    
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
                    <h3 class="mb-0 text-center">Edit your Company Profile</h3>
                    <!-- Progress Steps -->
                    <div class="progress mt-3">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 25%" id="progress-bar"></div>
                    </div>
                    <div class="step-indicators d-flex justify-content-between mt-2">
                        <span class="step-indicator active" id="step-1">1. Company Info</span>
                        <span class="step-indicator" id="step-2">2. Company Details</span>
                        <span class="step-indicator" id="step-3">3. Contact & Location</span>
                        <span class="step-indicator" id="step-4">4. Document Verification</span>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('employers.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                   
                        <!-- Section 1: Company Information -->
                        <div id="section-company-information" class="form-step active">
                            <h4 class="mb-4">Company Information</h4>
                            <p class="text-muted mb-4"><span class="text-danger">*</span> Required field</p>
                            
                            @if($profile && $profile->employer_type)
                                <div class="mb-3">
                                    <label class="form-label">Employer Type</label>
                                    <div>
                                        <span class="badge bg-{{ $profile->employer_type === 'formal' ? 'primary' : 'success' }} fs-6">
                                            {{ ucfirst($profile->employer_type) }} Employer
                                        </span>
                                        <input type="hidden" name="employer_type" value="{{ $profile->employer_type }}">
                                        <div class="form-text">
                                            <small class="text-muted">Employer type cannot be changed after registration.</small>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                                <input type="text" name="company_name" class="form-control" 
                                       value="{{ old('company_name', $profile->company_name ?? '') }}" required>
                                @error('company_name')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                           
                            <div class="mb-3">
                                <label for="company_logo" class="form-label">Company Logo</label>
                                <input type="file" name="company_logo" id="company_logo" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Upload your company logo (JPG, PNG, max 2MB)</small>
                                @if($profile && $profile->company_logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $profile->company_logo) }}" alt="Company Logo" class="img-thumbnail" style="max-width: 200px;" id="current-logo">
                                        <small class="text-muted d-block">Current logo</small>
                                    </div>
                                @endif
                            </div>

                            <div class="mt-4 text-end">
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Section 2: Company Details -->
                        <div id="section-company-details" class="form-step">
                            <h4 class="mb-4">Company Details</h4>
                            
                            <div class="mb-3">
                                <label for="company_type_id" class="form-label">Industry/Company Type</label>
                                <select name="company_type_id" id="company_type_id" class="form-control">
                                    <option value="">Select Industry</option>
                                    @if(isset($companyTypes))
                                        @foreach($companyTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                    {{ old('company_type_id', $profile->company_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                <div class="form-text">
                                    <small>Select the industry that best describes your company</small>
                                </div>
                                @error('company_type_id')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="mb-3">
                                <label for="company_description" class="form-label">Company Description</label>
                                <textarea name="company_description" id="company_description" class="form-control" rows="4" 
                                          placeholder="Tell us about your company, what you do, your mission...">{{ old('company_description', $profile->company_description ?? '') }}</textarea>
                                <div class="form-text">
                                    <small>This will be displayed on your company profile and job postings</small>
                                </div>
                                @error('company_description')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="website_url" class="form-label">Company Website</label>
                                        <input type="url" name="website_url" id="website_url" class="form-control" 
                                               value="{{ old('website_url', $profile->website_url ?? '') }}" 
                                               placeholder="https://www.yourcompany.com">
                                        @error('website_url')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="linkedin_url" class="form-label">LinkedIn Profile</label>
                                        <input type="url" name="linkedin_url" id="linkedin_url" class="form-control" 
                                               value="{{ old('linkedin_url', $profile->linkedin_url ?? '') }}" 
                                               placeholder="https://linkedin.com/company/yourcompany">
                                        @error('linkedin_url')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="founded_year" class="form-label">Founded Year</label>
                                        <input type="number" name="founded_year" id="founded_year" class="form-control" 
                                               value="{{ old('founded_year', $profile->founded_year ?? '') }}" 
                                               min="1800" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                                        @error('founded_year')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="company_size_min" class="form-label">Company Size (Min)</label>
                                        <input type="number" name="company_size_min" id="company_size_min" class="form-control" 
                                               value="{{ old('company_size_min', $profile->company_size_min ?? '') }}" 
                                               min="1" placeholder="e.g., 10">
                                        @error('company_size_min')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="company_size_max" class="form-label">Company Size (Max)</label>
                                        <input type="number" name="company_size_max" id="company_size_max" class="form-control" 
                                               value="{{ old('company_size_max', $profile->company_size_max ?? '') }}" 
                                               min="1" placeholder="e.g., 100">
                                        @error('company_size_max')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Previous</button>
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Section 3: Contact & Location -->
                        <div id="section-contact-location" class="form-step">
                            <h4 class="mb-4">Contact & Location Information</h4>
                            
                            <h5 class="mt-4 mb-3">Company Address</h5>
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
                                               value="{{ old('contactnumber', $user->contactnumber ?? '') }}" 
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
                                <button type="button" onclick="nextStep()" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        <!-- Section 4: Document Verification (Optional) -->
                        <div id="section-document-verification" class="form-step">
                            <h4 class="mb-4 text-primary">
                                <i class="fas fa-file-upload me-2"></i>Document Verification
                            </h4>
                            
                            @php
                                $verification = $user->companyVerification ?? null;
                            @endphp
                            
                            @if($verification && $verification->status === 'approved')
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Verified Company</strong> - Your company has been verified by our team on {{ $verification->verified_at->format('M d, Y') }}.
                                </div>
                            @elseif($verification && $verification->status === 'pending')
                                <div class="alert alert-info">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Verification Pending</strong> - Your documents are currently under review.
                                </div>
                            @elseif($verification && $verification->status === 'rejected')
                                <div class="alert alert-danger">
                                    <i class="fas fa-times-circle me-2"></i>
                                    <strong>Verification Rejected</strong> - {{ $verification->rejection_reason ?? 'Please contact admin for details.' }}
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Not Yet Verified</strong> - Upload your company verification documents to get verified.
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="business_registration_number" class="form-label">
                                            <i class="fas fa-building me-1"></i>Business Registration Number
                                        </label>
                                        <input type="text" name="business_registration_number" id="business_registration_number" class="form-control" 
                                               value="{{ old('business_registration_number', $verification->business_registration_number ?? '') }}"
                                               placeholder="DTI/SEC Registration Number">
                                        <div class="form-text">
                                            Enter your DTI or SEC registration number
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tax_id" class="form-label">
                                            <i class="fas fa-receipt me-1"></i>Tax Identification Number (TIN)
                                        </label>
                                        <input type="text" name="tax_id" id="tax_id" class="form-control" 
                                               value="{{ old('tax_id', $verification->tax_id ?? '') }}"
                                               placeholder="XXX-XXX-XXX-XXX">
                                        <div class="form-text">
                                            Enter your company's TIN
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="verification_document" class="form-label">
                                    <i class="fas fa-file-pdf me-1"></i>Business Registration Document
                                    <span class="text-success">(Optional)</span>
                                </label>
                                <input type="file" name="verification_document" id="verification_document" class="form-control" accept=".jpg,.jpeg,.png,.pdf">
                                <div class="form-text">
                                    Upload your DTI/SEC certificate, Mayor's Permit, or Business Permit (PDF, JPG, PNG, max 5MB)
                                </div>
                                @if($verification && $verification->verification_document_path)
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $verification->verification_document_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-file-download me-1"></i> View Current Document
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="verification_notes" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>Additional Notes
                                    <span class="text-muted">(Optional)</span>
                                </label>
                                <textarea name="verification_notes" id="verification_notes" class="form-control" rows="3" 
                                          placeholder="Any additional information for our verification team...">{{ old('verification_notes', $verification->verification_notes ?? '') }}</textarea>
                            </div>

                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Note:</strong> All documents will be reviewed by our admin team. 
                                Submitting new documents will reset your verification status to pending.
                            </div>

                            <div class="mt-4 d-flex justify-content-between">
                                <button type="button" onclick="prevStep()" class="btn btn-secondary">Previous</button>
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>Update Profile
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
