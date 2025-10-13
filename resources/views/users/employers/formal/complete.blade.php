
@extends('layouts.dashboard')

@section('content')
    <h1>Manage your Company profile</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 text-center">Complete your Company profile</h3>
                    @if(session('warning'))
                        <div class="alert alert-warning mt-3">
                            <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if(Auth::user()->employerProfile && !Auth::user()->employerProfile->employer_type)
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Profile Update Required</h5>
                            <p>We've updated our system to better serve different types of employers. Please select your employer type below to continue using the platform.</p>
                        </div>
                    @endif
                    <form action="{{ route('employers.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <!-- Personal information -->
                        <div id="section-personal-information">
                            <div class="mb-3">
                                <label for="company_name" class="form-label">Company Name</label>
                                <input type="text" name="company_name" class="form-control w-75" 
                                       value="{{ old('company_name', Auth::user()->employerProfile->company_name ?? '') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="employer_type" class="form-label">Employer Type <span class="text-danger">*</span></label>
                                                                <select name="employer_type" id="employer_type" class="form-control w-75" required>
                                    <option value="">Select Employer Type</option>
                                    <option value="formal" {{ old('employer_type') == 'formal' ? 'selected' : '' }}>Formal Employer (Registered Business)</option>
                                    <option value="informal" {{ old('employer_type') == 'informal' ? 'selected' : '' }}>Informal Employer (Household/Individual)</option>
                                </select>
                                <div class="form-text">
                                    <small>
                                        <strong>Formal Employer:</strong> Companies, businesses with DTI/SEC registration<br>
                                        <strong>Informal Employer:</strong> Households, individuals needing domestic help (maids, drivers, caregivers)
                                    </small>
                                </div>
                                @error('employer_type')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                           
                            <div class="mb-3">
                                <label for="company_logo" class="form-label">Company Logo</label>
                                <input type="file" name="company_logo" id="company_logo" class="form-control w-75">
                                @php $profile = Auth::user()->employerProfile; @endphp
                                @if($profile && $profile->company_logo)
                                    <div class="mt-2">
                                        <img src="{{ asset('storage/' . $profile->company_logo) }}" alt="Company Logo" style="max-width: 200px;">
                                        <small class="text-muted d-block">Current: {{ $profile->company_logo }}</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Company Details Section -->
                            <h3>Company Details</h3>
                            
                            <div class="mb-3">
                                <label for="company_type_id" class="form-label">Industry/Company Type</label>
                                <select name="company_type_id" id="company_type_id" class="form-control w-75">
                                    <option value="">Select Industry</option>
                                    @if(isset($companyTypes))
                                        @foreach($companyTypes as $type)
                                            <option value="{{ $type->id }}" 
                                                    {{ old('company_type_id') == $type->id ? 'selected' : '' }}>
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
                                <textarea name="company_description" id="company_description" class="form-control w-75" rows="4" 
                                          placeholder="Tell us about your company, what you do, your mission...">{{ old('company_description') }}</textarea>
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
                                               value="{{ old('website_url') }}" 
                                               placeholder="https://www.yourcompany.com">
                                        @error('website_url')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="linkedin_url" class="form-label">LinkedIn Profile</label>
                                        <input type="url" name="linkedin_url" id="linkedin_url" class="form-control" 
                                               value="{{ old('linkedin_url') }}" 
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
                                               value="{{ old('founded_year') }}" 
                                               min="1800" max="{{ date('Y') }}" placeholder="{{ date('Y') }}">
                                        @error('founded_year')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="company_size_min" class="form-label">Company Size (Min)</label>
                                        <input type="number" name="company_size_min" id="company_size_min" class="form-control" 
                                               value="{{ old('company_size_min') }}" 
                                               min="1" placeholder="e.g., 10">
                                        @error('company_size_min')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="company_size_max" class="form-label">Company Size (Max)</label>
                                        <input type="number" name="company_size_max" id="company_size_max" class="form-control" 
                                               value="{{ old('company_size_max') }}" 
                                               min="1" placeholder="e.g., 100">
                                        @error('company_size_max')<div class="text-danger">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            <h3>Location</h3>
                            <div class="mb-3">
                                <label for="street"  class="form-label">Street</label>
                                <input type="text" name="street" id="street" class="form-control w-75" 
                                       value="{{ old('street', $profile->street ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="barangay"  class="form-label">Barangay</label>
                                <input type="text" name="barangay" id="barangay" class="form-control w-75" 
                                       value="{{ old('barangay', $profile->barangay ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="municipality"  class="form-label">Municipality</label>
                                <input type="text" name="municipality" id="municipality" class="form-control w-75" 
                                       value="{{ old('municipality', $profile->municipality ?? '') }}">
                            </div>
                            <div class="mb-3">
                                <label for="province"  class="form-label">Province</label>
                                <input type="text" name="province" id="province" class="form-control w-75" 
                                       value="{{ old('province', $profile->province ?? '') }}">
                            </div>
                           
                            <div class="mb-3">
                                <label for="contactnumber"  class="form-label">Contact Number</label>
                                <input type="text" name="contactnumber" id="contactnumber"class="form-control w-75">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-muted">(Registered Email)</span></label>
                                <input type="email" name="email" id="email" class="form-control w-75 bg-light" 
                                       value="{{ Auth::user()->email }}" readonly>
                                <div class="form-text text-muted">
                                    <i class="fas fa-lock me-1"></i>This is your registered email. 
                                    <a href="{{ route('profile.edit') }}" class="text-primary">Change email in account settings</a>
                                </div>
                            </div>
                        </div>

                                                              
                        <button type="submit" class="btn btn-primary">Complete Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    
@endsection