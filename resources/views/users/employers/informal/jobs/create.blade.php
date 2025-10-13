@extends('layouts.dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-plus-circle me-2"></i>Post New Service Request</h1>
        <a href="{{ route('employers.jobs.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Jobs
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-home me-2"></i>Household Service Request</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employers.jobs.store') }}" method="POST">
                        @csrf
                        
                        <!-- Hidden field to mark as informal job -->
                        <input type="hidden" name="job_type" value="informal">

                        <!-- Basic Information -->
                        <div class="mb-3">
                            <label for="job_title" class="form-label">Service Needed <span class="text-danger">*</span></label>
                            <input type="text" name="job_title" id="job_title" class="form-control" 
                                   value="{{ old('job_title') }}" placeholder="e.g., House Maid, Driver, Caregiver, Gardener" required>
                            <div class="form-text">
                                <small><strong>Common services:</strong> House cleaning, cooking, childcare, elderly care, driving, gardening, security</small>
                            </div>
                            @error('job_title')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Service Details <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="4" 
                                      placeholder="Describe what you need, schedule, duties, and requirements..." required>{{ old('description') }}</textarea>
                            <div class="form-text">
                                <small><strong>Example:</strong> "Need house cleaner twice a week (Tuesdays & Fridays). Tasks include mopping, vacuuming, bathroom cleaning. Must be reliable and have references."</small>
                            </div>
                            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Location and Payment -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="location" class="form-control" 
                                       value="{{ old('location') }}" placeholder="Barangay, Municipality" required>
                                @error('location')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="salary" class="form-label">Daily Rate (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" name="salary" id="salary" class="form-control" 
                                           value="{{ old('salary') }}" step="0.01" min="0" placeholder="0.00">
                                </div>
                                <div class="form-text">
                                    <small>Leave blank to negotiate with applicants</small>
                                </div>
                                @error('salary')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Employment Type and People Needed -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="employment_type" class="form-label">Service Type <span class="text-danger">*</span></label>
                                <select name="employment_type" id="employment_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>Full-time/Live-in</option>
                                    <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part-time</option>
                                    <option value="temporary" {{ old('employment_type') == 'temporary' ? 'selected' : '' }}>Temporary/One-time</option>
                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract/Project</option>
                                </select>
                                @error('employment_type')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="positions_available" class="form-label">People Needed</label>
                                <input type="number" name="positions_available" id="positions_available" 
                                       class="form-control" value="{{ old('positions_available', 1) }}" min="1" max="10">
                                @error('positions_available')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Classification -->
                        <div class="mb-3">
                            <label for="classification" class="form-label">Service Category</label>
                            <select name="classification" id="classification" class="form-control">
                                <option value="">Select Category (Optional)</option>
                                @if(isset($jobClassifications))
                                    @foreach($jobClassifications as $classification)
                                        <option value="{{ $classification->id }}" 
                                                {{ old('classification') == $classification->id ? 'selected' : '' }}>
                                            {{ $classification->name }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('classification')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Skills & Requirements</label>
                            <textarea name="requirements" id="requirements" class="form-control" rows="3" 
                                      placeholder="Any specific skills, experience, or requirements? (Optional)">{{ old('requirements') }}</textarea>
                            @error('requirements')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Additional Information -->
                        <div class="mb-3">
                            <label for="benefits" class="form-label">Benefits & Perks</label>
                            <textarea name="benefits" id="benefits" class="form-control" rows="2" 
                                      placeholder="Meals provided, transportation allowance, bonuses, etc. (Optional)">{{ old('benefits') }}</textarea>
                            @error('benefits')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="status" value="open">

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('employers.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-1"></i>Post Service Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection