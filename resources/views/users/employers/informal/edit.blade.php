
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
                            <label for="email"  class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control w-75" value="{{ old('email', $user->email ?? '') }}">
                        </div>
                    </div>

                                                          
                        <button type="submit" class="btn btn-primary">Update Profile</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection