@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex align-items-center">
                <a href="{{ route('messages.inbox') }}" class="btn btn-outline-secondary btn-sm me-3">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
                <div>
                    <h1 class="h3 fw-bold text-primary mb-0">
                        <i class="bi bi-pencil-square"></i> Compose New Message
                    </h1>
                    <p class="text-muted mb-0">Send a message to another user</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Compose Form -->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('messages.send') }}">
                        @csrf
                        
                        <!-- Recipient Selection -->
                        <div class="mb-4">
                            <label for="receiver_id" class="form-label fw-bold">
                                To: <span class="text-danger">*</span>
                            </label>
                            <select 
                                name="receiver_id" 
                                id="receiver_id" 
                                class="form-select @error('receiver_id') is-invalid @enderror" 
                                required>
                                <option value="">-- Select Recipient --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('receiver_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} 
                                        @if($user->role === 'employer')
                                            (Employer)
                                        @elseif($user->role === 'seeker')
                                            (Job Seeker)
                                        @else
                                            (Admin)
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('receiver_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if(Auth::user()->role === 'employer')
                                <small class="form-text text-muted">
                                    <i class="bi bi-info-circle"></i> You can send messages to job seekers
                                </small>
                            @elseif(Auth::user()->role === 'seeker')
                                <small class="form-text text-muted">
                                    <i class="bi bi-info-circle"></i> You can send messages to employers
                                </small>
                            @endif
                        </div>

                        <!-- Message Content -->
                        <div class="mb-4">
                            <label for="message_content" class="form-label fw-bold">
                                Message: <span class="text-danger">*</span>
                            </label>
                            <textarea 
                                name="message_content" 
                                id="message_content" 
                                class="form-control @error('message_content') is-invalid @enderror" 
                                rows="8" 
                                placeholder="Type your message here..."
                                maxlength="5000"
                                required>{{ old('message_content') }}</textarea>
                            @error('message_content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Maximum 5000 characters
                            </small>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('messages.inbox') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Card -->
            <div class="card border-0 bg-light mt-4">
                <div class="card-body">
                    <h6 class="fw-bold text-primary mb-3">
                        <i class="bi bi-lightbulb"></i> Messaging Tips
                    </h6>
                    <ul class="mb-0 small text-muted">
                        <li>Be professional and courteous in your messages</li>
                        <li>Clearly state the purpose of your message</li>
                        @if(Auth::user()->role === 'seeker')
                            <li>Use messages to inquire about job opportunities or application status</li>
                            <li>Keep your messages relevant to job applications</li>
                        @elseif(Auth::user()->role === 'employer')
                            <li>Use messages to communicate with applicants about interviews or job details</li>
                            <li>Respond promptly to job seeker inquiries</li>
                        @endif
                        <li>Do not share sensitive personal information</li>
                        <li>Report any inappropriate messages to the admin</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Character counter for message
document.getElementById('message_content').addEventListener('input', function() {
    const maxLength = 5000;
    const currentLength = this.value.length;
    const remaining = maxLength - currentLength;
    
    // Character counter (future enhancement)
});
</script>
@endsection
