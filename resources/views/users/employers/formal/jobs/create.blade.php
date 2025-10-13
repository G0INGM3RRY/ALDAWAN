@extends('layouts.dashboard')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="fas fa-plus-circle me-2"></i>Post New Job</h1>
        <a href="{{ route('employers.jobs.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i>Back to Jobs
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Job Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('employers.jobs.store') }}" method="POST">
                        @csrf

                        <!-- Job Basic Information -->
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                                <input type="text" name="job_title" id="job_title" class="form-control" 
                                       value="{{ old('job_title') }}" required>
                                @error('job_title')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="employment_type" class="form-label">Employment Type <span class="text-danger">*</span></label>
                                <select name="employment_type" id="employment_type" class="form-control" required>
                                    <option value="">Select Type</option>
                                    <option value="full_time" {{ old('employment_type') == 'full_time' ? 'selected' : '' }}>Full Time</option>
                                    <option value="part_time" {{ old('employment_type') == 'part_time' ? 'selected' : '' }}>Part Time</option>
                                    <option value="contract" {{ old('employment_type') == 'contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="temporary" {{ old('employment_type') == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                    <option value="internship" {{ old('employment_type') == 'internship' ? 'selected' : '' }}>Internship</option>
                                </select>
                                @error('employment_type')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control" rows="4" 
                                      placeholder="Describe the job responsibilities, requirements, and expectations..." required>{{ old('description') }}</textarea>
                            @error('description')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Location and Salary -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                <input type="text" name="location" id="location" class="form-control" 
                                       value="{{ old('location') }}" placeholder="City, Province" required>
                                @error('location')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="salary" class="form-label">Salary Range</label>
                                <input type="number" name="salary" id="salary" class="form-control" 
                                       value="{{ old('salary') }}" step="0.01" min="0" placeholder="Monthly salary (PHP)">
                                @error('salary')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <!-- Job Classification and Requirements -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="classification" class="form-label">Job Classification</label>
                                <select name="classification" id="classification" class="form-control">
                                    <option value="">Select Classification</option>
                                    @foreach($jobClassifications as $classification)
                                        <option value="{{ $classification->id }}" 
                                                {{ old('classification') == $classification->id ? 'selected' : '' }}>
                                            {{ $classification->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('classification')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="positions_available" class="form-label">Positions Available</label>
                                <input type="number" name="positions_available" id="positions_available" 
                                       class="form-control" value="{{ old('positions_available', 1) }}" min="1">
                                @error('positions_available')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="requirements" class="form-label">Job Requirements</label>
                            <textarea name="requirements" id="requirements" class="form-control" rows="3" 
                                      placeholder="Education, experience, skills, and other requirements...">{{ old('requirements') }}</textarea>
                            @error('requirements')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="benefits" class="form-label">Benefits & Perks</label>
                            <textarea name="benefits" id="benefits" class="form-control" rows="2" 
                                      placeholder="Health insurance, paid leaves, bonuses, etc...">{{ old('benefits') }}</textarea>
                            @error('benefits')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Experience and Education Requirements -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="minimum_experience_years" class="form-label">Minimum Experience (Years)</label>
                                <select name="minimum_experience_years" id="minimum_experience_years" class="form-control">
                                    <option value="0" {{ old('minimum_experience_years') == '0' ? 'selected' : '' }}>No experience required</option>
                                    <option value="1" {{ old('minimum_experience_years') == '1' ? 'selected' : '' }}>1 year</option>
                                    <option value="2" {{ old('minimum_experience_years') == '2' ? 'selected' : '' }}>2 years</option>
                                    <option value="3" {{ old('minimum_experience_years') == '3' ? 'selected' : '' }}>3 years</option>
                                    <option value="5" {{ old('minimum_experience_years') == '5' ? 'selected' : '' }}>5+ years</option>
                                    <option value="10" {{ old('minimum_experience_years') == '10' ? 'selected' : '' }}>10+ years</option>
                                </select>
                                @error('minimum_experience_years')<div class="text-danger">{{ $message }}</div>@enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <div class="form-check">
                                    <input type="checkbox" name="remote_work_available" id="remote_work_available" 
                                           class="form-check-input" value="1" {{ old('remote_work_available') ? 'checked' : '' }}>
                                    <label for="remote_work_available" class="form-check-label">
                                        Remote Work Available
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Accessibility and Disability Restrictions -->
                        <div class="mb-3">
                            <label class="form-label">Disability Restrictions</label>
                            <div class="form-text mb-2">Select any disabilities that may prevent someone from performing this job safely:</div>
                            <div class="row">
                                @foreach($disabilities as $disability)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input type="checkbox" name="disability_restrictions[]" 
                                                   id="disability_{{ $disability->id }}" class="form-check-input" 
                                                   value="{{ $disability->id }}"
                                                   {{ is_array(old('disability_restrictions')) && in_array($disability->id, old('disability_restrictions')) ? 'checked' : '' }}>
                                            <label for="disability_{{ $disability->id }}" class="form-check-label">
                                                {{ $disability->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('disability_restrictions')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="accessibility_notes" class="form-label">Accessibility Notes</label>
                            <textarea name="accessibility_notes" id="accessibility_notes" class="form-control" rows="2" 
                                      placeholder="Additional information about workplace accessibility...">{{ old('accessibility_notes') }}</textarea>
                            @error('accessibility_notes')<div class="text-danger">{{ $message }}</div>@enderror
                        </div>

                        <!-- Hidden Fields -->
                        <input type="hidden" name="job_type" value="formal">
                        <input type="hidden" name="status" value="open">

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('employers.jobs.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Post Job
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection