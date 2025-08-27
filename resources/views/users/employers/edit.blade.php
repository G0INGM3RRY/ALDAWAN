
@extends('layouts.dashboard')

@section('content')
    <h1>Manage your Company profile</h1>
    <div class="row">        
        <!-- Main Content Area -->
        <div class="col-md-9">
          
            <form action="{{ route('employers.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
               @method('PUT')
                    <!-- Personal information -->
                    <div id="section-personal-information">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name</label>
                            <input type="text" name="company_name" class="form-control" value="{{ $profile->company_name ?? '' }}" required>
                        </div>
                       
                        <div class="mb-3">
                            <label for="company_logo" class="form-label">Company Logo</label>
                            <input type="file" name="company_logo" id="company_logo" class="form-control">
                            @if($profile && $profile->company_logo)
                                <div class="mt-2">
                                    <img src="{{ asset('storage/' . $profile->company_logo) }}" alt="Company Logo" style="max-width: 200px;">
                                </div>
                            @endif
                        </div>

                        <h3>Location</h3>
                        <div class="mb-3">
                            <label for="street"  class="form-label">Street</label>
                            <input type="text" name="street" id="street" class="form-control" value="{{ $profile->street ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="barangay"  class="form-label">Barangay</label>
                            <input type="text" name="barangay" id="barangay" class="form-control" value="{{ $profile->barangay ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="municipality"  class="form-label">Municipality</label>
                            <input type="text" name="municipality" id="municipality" class="form-control" value="{{ $profile->municipality ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="province"  class="form-label">Province</label>
                            <input type="text" name="province" id="province" class="form-control" value="{{ $profile->province ?? '' }}">
                        </div>
                       
                        <div class="mb-3">
                            <label for="contactnumber"  class="form-label">Contact Number</label>
                            <input type="text" name="contactnumber" id="contactnumber" class="form-control" value="{{ $user->contactnumber ?? '' }}">
                        </div>
                        <div class="mb-3">
                            <label for="email"  class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" value="{{ $user->email ?? '' }}">
                        </div>
                    </div>

                                                          
                    <button type="submit" class="btn btn-primary">Next</button>
                </div>
            </form>
        </div>
    </div>
@endsection