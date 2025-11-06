@extends('layouts.admin')
@section('title', 'Edit Job Posting')
@section('page-title', 'Edit Job Posting')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Job Posting (Admin)</h3>
                    <small>Edit job posting: {{ $job->job_title }}</small>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4"><span class="text-danger">*</span> Required field</p>
                    
                    <form action="{{ route('admin.jobs.update', $job) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <!-- Employer Selection -->
                        <div class="mb-4 p-3 bg-light border rounded">
                            <h5 class="mb-3"><i class="fas fa-building me-2"></i>Select Employer</h5>
                            <div class="mb-3">
                                <label for="employer_id" class="form-label">Employer <span class="text-danger">*</span></label>
                                <select class="form-control @error('employer_id') is-invalid @enderror" 
                                        id="employer_id" name="employer_id" required>
                                    <option value="">-- Select Employer --</option>
                                    @foreach($employers as $employer)
                                        <option value="{{ $employer->id }}" {{ old('employer_id', $job->user_id) == $employer->id ? 'selected' : '' }}>
                                            {{ $employer->name }} 
                                            @if($employer->employer)
                                                ({{ ucfirst($employer->employer->employer_type) }})
                                                @if($employer->employer->company_name)
                                                    - {{ $employer->employer->company_name }}
                                                @endif
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @error('employer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Select the employer this job posting belongs to</small>
                            </div>
                        </div>

                        <!-- Job Title -->
                        <div class="mb-3">
                            <label for="job_title" class="form-label">Job Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('job_title') is-invalid @enderror" 
                                   id="job_title" name="job_title" value="{{ old('job_title', $job->job_title) }}" required>
                            @error('job_title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Job Description -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Job Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="5" required>{{ old('description', $job->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Requirements -->
                        <div class="mb-3">
                            <label for="requirements" class="form-label">Job Requirements <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('requirements') is-invalid @enderror" 
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
                                    <input type="number" step="0.01" class="form-control @error('salary') is-invalid @enderror" 
                                           id="salary" name="salary" value="{{ old('salary', $job->salary) }}" required min="0"
                                           title="Enter salary amount" placeholder="e.g., 15000">
                                    @error('salary')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location', $job->location) }}" required>
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Employment Type and Status Row -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="employment_type" class="form-label">Employment Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('employment_type') is-invalid @enderror" 
                                            id="employment_type" name="employment_type" required>
                                        <option value="">Select Employment Type</option>
                                        <option value="full_time" {{ old('employment_type', $job->employment_type) == 'full_time' ? 'selected' : '' }}>Full-time</option>
                                        <option value="part_time" {{ old('employment_type', $job->employment_type) == 'part_time' ? 'selected' : '' }}>Part-time</option>
                                        <option value="contract" {{ old('employment_type', $job->employment_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                        <option value="temporary" {{ old('employment_type', $job->employment_type) == 'temporary' ? 'selected' : '' }}>Temporary</option>
                                        <option value="internship" {{ old('employment_type', $job->employment_type) == 'internship' ? 'selected' : '' }}>Internship</option>
                                    </select>
                                    @error('employment_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Job Status</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status">
                                        <option value="open" {{ old('status', $job->status) == 'open' ? 'selected' : '' }}>Open</option>
                                        <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Primary Classification -->
                        <div class="mb-3">
                            <label for="classification" class="form-label">Primary Job Classification <span class="text-danger">*</span></label>
                            <select class="form-control @error('classification') is-invalid @enderror" 
                                    id="classification" name="classification" required>
                                <option value="">Select Classification</option>
                                @foreach($classifications as $classif)
                                    <option value="{{ $classif->name }}" {{ old('classification', $job->classification) == $classif->name ? 'selected' : '' }}>
                                        {{ $classif->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('classification')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Classifications -->
                        <div class="mb-3">
                            <label class="form-label">Additional Classifications (Optional)</label>
                            <div class="row">
                                @foreach($classifications as $classification)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="additional_classifications[]" 
                                                   value="{{ $classification->id }}" 
                                                   id="class_{{ $classification->id }}"
                                                   {{ (is_array(old('additional_classifications')) && in_array($classification->id, old('additional_classifications'))) || (!old('additional_classifications') && $job->classifications->contains($classification->id)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="class_{{ $classification->id }}">
                                                {{ $classification->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Education and Experience -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="minimum_education_level_id" class="form-label">Minimum Education Level</label>
                                    <select class="form-control @error('minimum_education_level_id') is-invalid @enderror" 
                                            id="minimum_education_level_id" name="minimum_education_level_id">
                                        <option value="">No Requirement</option>
                                        @foreach($educationLevels as $level)
                                            <option value="{{ $level->id }}" {{ old('minimum_education_level_id', $job->minimum_education_level_id) == $level->id ? 'selected' : '' }}>
                                                {{ $level->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('minimum_education_level_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="minimum_experience_years" class="form-label">Minimum Experience (Years)</label>
                                    <input type="number" class="form-control @error('minimum_experience_years') is-invalid @enderror" 
                                           id="minimum_experience_years" name="minimum_experience_years" 
                                           value="{{ old('minimum_experience_years', $job->minimum_experience_years) }}" min="0" placeholder="0"
                                           title="Enter minimum years of experience">
                                    @error('minimum_experience_years')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Required Skills -->
                        <div class="mb-3">
                            <label class="form-label">Required Skills (Optional)</label>
                            <div class="row">
                                @foreach($skills as $skill)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="required_skills[]" 
                                                   value="{{ $skill->id }}" 
                                                   id="skill_{{ $skill->id }}"
                                                   {{ (is_array(old('required_skills')) && in_array($skill->id, old('required_skills'))) || (!old('required_skills') && $job->requiredSkills->contains($skill->id)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="skill_{{ $skill->id }}">
                                                {{ $skill->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Disability Accommodations -->
                        <div class="mb-3">
                            <label class="form-label">Suitable for Persons with Disabilities (Optional)</label>
                            <div class="row">
                                @foreach($disabilities as $disability)
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   name="disabilities[]" 
                                                   value="{{ $disability->id }}" 
                                                   id="disability_{{ $disability->id }}"
                                                   {{ (is_array(old('disabilities')) && in_array($disability->id, old('disabilities'))) || (!old('disabilities') && $job->disabilities->contains($disability->id)) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="disability_{{ $disability->id }}">
                                                {{ $disability->name }}
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Disability Notes -->
                        <div class="mb-3">
                            <label for="accessibility_notes" class="form-label">Accessibility/Accommodation Notes</label>
                            <textarea class="form-control @error('accessibility_notes') is-invalid @enderror" 
                                      id="accessibility_notes" name="accessibility_notes" rows="3" 
                                      placeholder="Describe any accommodations or accessibility features available">{{ old('accessibility_notes', $job->accessibility_notes) }}</textarea>
                            @error('accessibility_notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Example: "Office is wheelchair accessible", "Sign language interpreter available", etc.
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.jobs') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Update Job Posting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
