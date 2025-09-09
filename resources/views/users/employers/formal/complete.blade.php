
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
                                    <option value="formal" {{ old('employer_type') == 'formal' ? 'selected' : '' }}>Formal Employer</option>
                                    <option value="informal" {{ old('employer_type') == 'informal' ? 'selected' : '' }}>Informal Employer</option>
                                </select>
                                <div class="form-text">
                                    <small>
                                        <strong>Formal:</strong> Registered companies, corporations, government agencies<br>
                                        <strong>Informal:</strong> Individual contractors, small businesses, household employers
                                    </small>
                                </div>
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
                                <label for="email"  class="form-label">Email</label>
                                <input type="email" name="email" id="email" class="form-control w-75">
                            </div>
                        </div>

                                                              
                        <button type="submit" class="btn btn-primary">Complete Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    
@endsection