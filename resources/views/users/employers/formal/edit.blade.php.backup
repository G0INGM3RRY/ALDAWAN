
@extends('layouts.dashboard')

@section('content')
    <h1>Manage your Company profile</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0 text-center">Edit your Company profile</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('employers.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        
                    <!-- Personal information -->
                    <div id="section-personal-information">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control w-75" value="{{ old('company_name', $profile->company_name ?? '') }}" required>
                        </div>

                        @if($profile && $profile->employer_type)
                            <div class="mb-3">
                                <label class="form-label">Employer Type</label>
                                <div class="w-75">
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
                            <label for="company_logo" class="form-label">Company Logo</label>
                            <input type="file" name="company_logo" id="company_logo" class="form-control w-75">
                            @if($profile && $profile->company_logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $profile->company_logo) }}" alt="Company Logo" style="max-width: 200px;">
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
                                                {{ old('company_type_id', $profile->company_type_id ?? '') == $type->id ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('company_type_id')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="company_description" class="form-label">Company Description</label>
                            <textarea name="company_description" id="company_description" class="form-control w-75" rows="4" 
                                      placeholder="Tell us about your company, what you do, your mission...">{{ old('company_description', $profile->company_description ?? '') }}</textarea>
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

                        @if($profile && $profile->is_verified)
                            <div class="mb-3">
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <strong>Verified Company</strong> - Your company has been verified by our team.
                                </div>
                            </div>
                        @else
                            <div class="mb-3">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Verification Pending</strong> - Complete your profile to apply for company verification.
                                </div>
                            </div>
                        @endif

                        <h3>Location</h3>
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
                            <label for="contactnumber"  class="form-label">Contact Number</label>
                            <input type="text" name="contactnumber" id="contactnumber" class="form-control w-75" value="{{ old('contactnumber', $user->contactnumber ?? '') }}">
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

                                                          
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection