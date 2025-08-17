@extends('layouts.dashboard')
@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Post a New Job</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('employers.jobs.store') }}" method="POST">
                        @csrf
                        
                        <!-- Job Title -->
                        <div class="mb-3">
                            <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('job_title') is-invalid @enderror" 
                                   id="job_title" name="job_title" value="{{ old('job_title') }}" required>
                            @error('job_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Job Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Requirements -->
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Job Requirements <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" 
                                      id="requirements" name="requirements" rows="4" required>{{ old('requirements') }}</textarea>
                            @error('requirements')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Salary and Location Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="salary" class="form-label">Salary (PHP) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror" 
                                           id="salary" name="salary" value="{{ old('salary') }}" required>
                                    @error('salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location') }}" required>
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
                                    <select class="form-control @error('employment_type') is-invalid @enderror" 
                                            id="employment_type" name="employment_type" required>
                                        <option value="">Select Employment Type</option>
                                        <option value="full-time" {{ old('employment_type') == 'full-time' ? 'selected' : '' }}>Full-time</option>
                                        <option value="part-time" {{ old('employment_type') == 'part-time' ? 'selected' : '' }}>Part-time</option>
                                        <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="freelance" {{ old('employment_type') == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                        <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                    </select>
                                    @error('employment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="classification" class="form-label">Job Classification <span class="text-danger">*</span></label>
                                    <select class="form-control @error('classification') is-invalid @enderror" 
                                            id="classification" name="classification" required>
                                        <option value="">Select Classification</option>
                                        <option value="Information Technology" {{ old('classification') == 'Information Technology' ? 'selected' : '' }}>Information Technology</option>
                                        <option value="Customer Service" {{ old('classification') == 'Customer Service' ? 'selected' : '' }}>Customer Service</option>
                                        <option value="Marketing" {{ old('classification') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                                        <option value="Administrative" {{ old('classification') == 'Administrative' ? 'selected' : '' }}>Administrative</option>
                                        <option value="Creative" {{ old('classification') == 'Creative' ? 'selected' : '' }}>Creative</option>
                                        <option value="Sales" {{ old('classification') == 'Sales' ? 'selected' : '' }}>Sales</option>
                                        <option value="Finance" {{ old('classification') == 'Finance' ? 'selected' : '' }}>Finance</option>
                                        <option value="Healthcare" {{ old('classification') == 'Healthcare' ? 'selected' : '' }}>Healthcare</option>
                                        <option value="Education" {{ old('classification') == 'Education' ? 'selected' : '' }}>Education</option>
                                        <option value="Manufacturing" {{ old('classification') == 'Manufacturing' ? 'selected' : '' }}>Manufacturing</option>
                                        <option value="Other" {{ old('classification') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('classification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Status (Hidden, defaults to 'open') -->
                        <input type="hidden" name="status" value="open">

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('employers.dashboard') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Post Job</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection