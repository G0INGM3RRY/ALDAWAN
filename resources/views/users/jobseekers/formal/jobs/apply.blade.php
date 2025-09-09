@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Job Summary Card -->
            <div class="card border-primary mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        @if(isset($editMode) && $editMode)
                            Edit Application for Position
                        @else
                            Applying for Position
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    <h4 class="text-dark">{{ $job->job_title }}</h4>
                    <h6 class="text-primary mb-2">{{ $job->classification }}</h6>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <span class="fw-semibold">Salary:</span> ₱{{ number_format($job->salary, 2) }}
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <span class="fw-semibold">Type:</span> {{ ucfirst($job->employment_type) }}
                            </small>
                        </div>
                        <div class="col-12">
                            <small class="text-muted">
                                <span class="fw-semibold">Location:</span> {{ $job->location }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Application Form -->
            <div class="card border-0 shadow">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-primary">
                        @if(isset($editMode) && $editMode)
                            Edit Your Application
                        @else
                            Submit Your Application
                        @endif
                    </h5>
                    <small class="text-muted">
                        @if(isset($editMode) && $editMode)
                            Update your information and documents for this position
                        @else
                            Provide your information and documents for this position
                        @endif
                    </small>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="@if(isset($editMode) && $editMode){{ route('jobseekers.applications.update', $application->id) }}@else{{ route('jobs.apply.store', $job->id) }}@endif" enctype="multipart/form-data">
                        @csrf
                        @if(isset($editMode) && $editMode)
                            @method('PUT')
                        @endif
                        
                        <!-- Cover Letter -->
                        <div class="mb-4">
                            <label for="cover_letter" class="form-label">
                                Cover Letter 
                                <span class="text-muted">(Optional)</span>
                            </label>
                            <textarea 
                                class="form-control @error('cover_letter') is-invalid @enderror" 
                                id="cover_letter" 
                                name="cover_letter" 
                                rows="5" 
                                maxlength="2000"
                                placeholder="Dear Hiring Manager,&#10;&#10;I am writing to express my interest in this position...">{{ old('cover_letter', isset($application) ? $application->cover_letter : '') }}</textarea>
                            <div class="form-text">Maximum 2000 characters. <span id="cover_letter_count">0</span>/2000</div>
                            @error('cover_letter')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Resume Upload -->
                        <div class="mb-4">
                            <label for="resume_file" class="form-label">
                                Resume/CV 
                                <span class="text-muted">(Optional)</span>
                            </label>
                            
                            @if(isset($application) && $application->resume_file_path)
                                <div class="mb-2">
                                    <small class="text-muted">Current resume:</small>
                                    <a href="{{ Storage::url($application->resume_file_path) }}" target="_blank" class="d-block text-primary">
                                        📄 {{ basename($application->resume_file_path) }}
                                    </a>
                                    <small class="text-muted">Upload a new file to replace the current one</small>
                                </div>
                            @endif
                            
                            <input 
                                type="file" 
                                class="form-control @error('resume') is-invalid @enderror" 
                                id="resume_file" 
                                name="resume"
                                accept=".pdf,.doc,.docx">
                            <div class="form-text">Supported formats: PDF, DOC, DOCX (Max 2MB)</div>
                            @error('resume')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Additional Documents -->
                        <div class="mb-4">
                            <label for="additional_documents" class="form-label">
                                Supporting Documents 
                                <span class="text-muted">(Optional)</span>
                            </label>
                            
                            @if(isset($application) && $application->additional_documents && count($application->additional_documents) > 0)
                                <div class="mb-2">
                                    <small class="text-muted">Current documents:</small>
                                    @foreach($application->additional_documents as $doc)
                                        <a href="{{ Storage::url($doc) }}" target="_blank" class="d-block text-primary">
                                            📄 {{ basename($doc) }}
                                        </a>
                                    @endforeach
                                    <small class="text-muted">Upload new files to replace current documents</small>
                                </div>
                            @endif
                            
                            <input 
                                type="file" 
                                class="form-control @error('additional_documents.*') is-invalid @enderror" 
                                id="additional_documents" 
                                name="additional_documents[]"
                                multiple
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <div class="form-text">
                                Upload certificates, transcripts, portfolio, or other relevant documents<br>
                                Supported formats: PDF, DOC, DOCX, JPG, PNG (Max 2MB each)
                            </div>
                            @error('additional_documents.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Quick Apply Option -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="quick_apply" name="quick_apply">
                                <label class="form-check-label text-muted" for="quick_apply">
                                    Submit basic application now (you can upload documents later)
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3">
                            <button type="submit" class="btn btn-primary flex-fill">
                                @if(isset($editMode) && $editMode)
                                    Update Application
                                @else
                                    Submit Application
                                @endif
                            </button>
                            @if(isset($editMode) && $editMode)
                                <a href="{{ route('jobseekers.applications') }}" class="btn btn-outline-secondary">
                                    Back to Applications
                                </a>
                            @else
                                <a href="{{ route('jobs.show', $job->id) }}" class="btn btn-outline-secondary">
                                    Cancel
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Character counter for cover letter
    const coverLetter = document.getElementById('cover_letter');
    const counter = document.getElementById('cover_letter_count');
    
    function updateCounter() {
        const length = coverLetter.value.length;
        counter.textContent = length;
        counter.className = length > 1800 ? 'text-warning' : length > 1950 ? 'text-danger' : '';
    }
    
    coverLetter.addEventListener('input', updateCounter);
    updateCounter(); // Initial count
    
    // File upload validation
    document.querySelectorAll('input[type="file"]').forEach(input => {
        input.addEventListener('change', function() {
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            
            Array.from(this.files).forEach(file => {
                if (file.size > maxSize) {
                    alert(`File "${file.name}" is too large. Maximum size is 2MB.`);
                    this.value = '';
                }
            });
        });
    });
    
    // Quick apply functionality
    const quickApply = document.getElementById('quick_apply');
    const fileFields = document.querySelectorAll('input[type="file"]');
    
    if (quickApply) {
        quickApply.addEventListener('change', function() {
            fileFields.forEach(field => {
                field.disabled = this.checked;
                if (this.checked) {
                    field.value = '';
                }
            });
        });
    }
});
</script>
@endsection
