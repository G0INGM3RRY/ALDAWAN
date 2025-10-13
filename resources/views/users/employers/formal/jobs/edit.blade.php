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
                                    <label for="classification_id" class="form-label">Job Classification <span class="text-danger">*</span></label>
                                    <select class="form-control w-75 @error('classification_id') is-invalid @enderror" 
                                            id="classification_id" name="classification_id" required>
                                        <option value="">Select Classification</option>
                                        
                                        @if(auth()->user()->employerProfile && auth()->user()->employerProfile->employer_type === 'informal')
                                            @foreach($jobClassifications->where('type', 'informal') as $classification)
                                                <option value="{{ $classification->id }}" 
                                                        {{ old('classification_id', $job->classification_id) == $classification->id ? 'selected' : '' }}>
                                                    {{ $classification->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            @foreach($jobClassifications->where('type', 'formal') as $classification)
                                                <option value="{{ $classification->id }}" 
                                                        {{ old('classification_id', $job->classification_id) == $classification->id ? 'selected' : '' }}>
                                                    {{ $classification->name }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('classification_id')
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

                        <!-- Disability Restrictions Section -->
                        <div class="mb-4">
                            <h5 class="text-primary mb-3">
                                <i class="fas fa-universal-access me-2"></i>Accessibility & Disability Considerations
                            </h5>
                            
                            <div class="alert alert-info">
                                <small>
                                    <strong>Important:</strong> Select any disabilities that may prevent safe or effective performance of this job. 
                                    This helps ensure job matches are appropriate and promotes workplace safety.
                                </small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">
                                    Disability Restrictions <span class="text-muted">(Optional)</span>
                                </label>
                                <div class="form-text mb-2">
                                    <small class="text-muted">
                                        Select disabilities that may not be compatible with the essential functions of this position.
                                    </small>
                                </div>
                                
                                @if(isset($disabilities) && $disabilities->count() > 0)
                                    <div class="row">
                                        @foreach($disabilities as $disability)
                                            <div class="col-md-6 col-lg-4 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" 
                                                           name="disability_restrictions[]" 
                                                           value="{{ $disability->id }}" 
                                                           id="edit_disability_{{ $disability->id }}"
                                                           {{ in_array($disability->id, old('disability_restrictions', $job->disability_restrictions ?? [])) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="edit_disability_{{ $disability->id }}">
                                                        {{ $disability->name }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">No disabilities data available.</p>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="accessibility_notes" class="form-label">
                                    Accessibility Notes <span class="text-muted">(Optional)</span>
                                </label>
                                <textarea class="form-control" 
                                          id="accessibility_notes" 
                                          name="accessibility_notes" 
                                          rows="3" 
                                          placeholder="Provide additional information about workplace accessibility, accommodations available, or specific requirements...">{{ old('accessibility_notes', $job->accessibility_notes) }}</textarea>
                                <div class="form-text">
                                    <small class="text-muted">
                                        Example: "Office is wheelchair accessible", "Sign language interpreter available", 
                                        "Job requires driving", "Heavy lifting required", etc.
                                    </small>
                                </div>
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
