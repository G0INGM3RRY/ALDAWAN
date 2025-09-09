@extends('layouts.dashboard')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Edit Job Posting</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('employers.jobs.update', $job->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <!-- Job Title -->
                        <div class="mb-3">
                            <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control w-75 @error('job_title') is-invalid @enderror" 
                                   id="job_title" name="job_title" value="{{ old('job_title', $job->job_title) }}" required>
                            @error('job_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Job Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                            <textarea class="form-control w-75 @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $job->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Requirements -->
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Job Requirements <span class="text-danger">*</span></label>
                            <textarea class="form-control w-75 @error('requirements') is-invalid @enderror" 
                                      id="requirements" name="requirements" rows="4" required>{{ old('requirements', $job->requirements) }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Salary and Location Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="salary" class="form-label">Salary (PHP) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control w-75 @error('salary') is-invalid @enderror" 
                                           id="salary" name="salary" value="{{ old('salary', $job->salary) }}" required>
                                    @error('salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control w-75 @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location', $job->location) }}" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Employment Type and Classification Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employment_type" class="form-label">Employment Type <span class="text-danger">*</span></label>
                                    <select class="form-control w-75 @error('employment_type') is-invalid @enderror" 
                                            id="employment_type" name="employment_type" required>
                                        <option value="">Select Employment Type</option>
                                        <option value="full-time" {{ old('employment_type', $job->employment_type) == 'full-time' ? 'selected' : '' }}>Full-time</option>
                                        <option value="part-time" {{ old('employment_type', $job->employment_type) == 'part-time' ? 'selected' : '' }}>Part-time</option>
                                        <option value="contract" {{ old('employment_type', $job->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="freelance" {{ old('employment_type', $job->employment_type) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                        <option value="internship" {{ old('employment_type', $job->employment_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                                    </select>
                                    @error('employment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="classification" class="form-label">Job Classification <span class="text-danger">*</span></label>
                                    <select class="form-control w-75 @error('classification') is-invalid @enderror" 
                                            id="classification" name="classification" required>
                                        <option value="">Select Classification</option>
                                        
                                        @if(auth()->user()->employerProfile && auth()->user()->employerProfile->employer_type === 'informal')
                                            <!-- Informal Employer Classifications -->
                                            <option value="Household Services" {{ old('classification', $job->classification) == 'Household Services' ? 'selected' : '' }}>Household Services</option>
                                            <option value="Construction/Labor" {{ old('classification', $job->classification) == 'Construction/Labor' ? 'selected' : '' }}>Construction/Labor</option>
                                            <option value="Food Service" {{ old('classification', $job->classification) == 'Food Service' ? 'selected' : '' }}>Food Service</option>
                                            <option value="Retail/Sales" {{ old('classification', $job->classification) == 'Retail/Sales' ? 'selected' : '' }}>Retail/Sales</option>
                                            <option value="Delivery/Transport" {{ old('classification', $job->classification) == 'Delivery/Transport' ? 'selected' : '' }}>Delivery/Transport</option>
                                            <option value="Care Services" {{ old('classification', $job->classification) == 'Care Services' ? 'selected' : '' }}>Care Services</option>
                                            <option value="Repair/Maintenance" {{ old('classification', $job->classification) == 'Repair/Maintenance' ? 'selected' : '' }}>Repair/Maintenance</option>
                                            <option value="Agriculture" {{ old('classification', $job->classification) == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
                                            <option value="General Labor" {{ old('classification', $job->classification) == 'General Labor' ? 'selected' : '' }}>General Labor</option>
                                            <option value="Cleaning Services" {{ old('classification', $job->classification) == 'Cleaning Services' ? 'selected' : '' }}>Cleaning Services</option>
                                            <option value="Security" {{ old('classification', $job->classification) == 'Security' ? 'selected' : '' }}>Security</option>
                                            <option value="Manufacturing" {{ old('classification', $job->classification) == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                        @else
                                            <!-- Formal Employer Classifications -->
                                            <option value="Information Technology" {{ old('classification', $job->classification) == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                            <option value="Customer Service" {{ old('classification', $job->classification) == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                                            <option value="Marketing" {{ old('classification', $job->classification) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                            <option value="Administrative" {{ old('classification', $job->classification) == 'Administrative' ? 'selected' : '' }}>Administrative</option>
                                            <option value="Creative" {{ old('classification', $job->classification) == 'Creative' ? 'selected' : '' }}>Creative</option>
                                            <option value="Sales" {{ old('classification', $job->classification) == 'Sales' ? 'selected' : '' }}>Sales</option>
                                            <option value="Finance" {{ old('classification', $job->classification) == 'Finance' ? 'selected' : '' }}>Finance</option>
                                            <option value="Healthcare" {{ old('classification', $job->classification) == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                            <option value="Education" {{ old('classification', $job->classification) == 'Education' ? 'selected' : '' }}>Education</option>
                                            <option value="Engineering" {{ old('classification', $job->classification) == 'Engineering' ? 'selected' : '' }}>Engineering</option>
                                            <option value="Legal" {{ old('classification', $job->classification) == 'Legal' ? 'selected' : '' }}>Legal</option>
                                            <option value="Management" {{ old('classification', $job->classification) == 'Management' ? 'selected' : '' }}>Management</option>
                                        @endif
                                        
                                        <option value="Other" {{ old('classification', $job->classification) == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('classification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Job Type Selection -->
                        <div class="mb-3">
                            <label for="job_type" class="form-label">Job Type <span class="text-danger">*</span></label>
                            <select class="form-control w-50 @error('job_type') is-invalid @enderror" 
                                    id="job_type" name="job_type" required>
                                <option value="">Select Job Type</option>
                                <option value="formal" {{ old('job_type', $job->job_type ?? 'formal') == 'formal' ? 'selected' : '' }}>
                                    Formal Position
                                </option>
                                <option value="informal" {{ old('job_type', $job->job_type ?? 'formal') == 'informal' ? 'selected' : '' }}>
                                    Informal/Contract Work
                                </option>
                            </select>
                            @error('job_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">
                                    <strong>Formal:</strong> Regular employment with benefits, contracts, and formal processes.<br>
                                    <strong>Informal:</strong> Contract work, gig economy, freelance, or temporary positions.
                                </small>
                            </div>
                        </div>

                        <!-- Job Status -->
                        <div class="mb-3">
                            <label for="status" class="form-label">Job Status <span class="text-danger">*</span></label>
                            <select class="form-control w-75 @error('status') is-invalid @enderror" 
                                    id="status" name="status" required>
                                <option value="open" {{ old('status', $job->status) == 'open' ? 'selected' : '' }}>Open - Accepting Applications</option>
                                <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed - Not Accepting Applications</option>
                                <option value="filled" {{ old('status', $job->status) == 'filled' ? 'selected' : '' }}>Filled - Position Occupied</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small>
                                    <strong>Open:</strong> Job is actively accepting applications<br>
                                    <strong>Closed:</strong> Job is temporarily not accepting applications<br>
                                    <strong>Filled:</strong> Position has been filled and is no longer available
                                </small>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employers.jobs.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Job</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
