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
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center">
                    <a href="{{ route('messages.inbox') }}" class="btn btn-outline-secondary btn-sm me-3">
                        <i class="bi bi-arrow-left"></i> Back to Inbox
                    </a>
                    
                    <div class="d-flex align-items-center">
                        <!-- User Avatar -->
                        @if($otherUser->role === 'employer')
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; font-size: 24px;">
                                🏢
                            </div>
                        @elseif($otherUser->role === 'seeker')
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; font-size: 24px;">
                                👤
                            </div>
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 50px; height: 50px; font-size: 24px;">
                                👨‍💼
                            </div>
                        @endif
                        
                        <div>
                            <h5 class="mb-0 fw-bold">{{ $displayName }}</h5>
                            <small class="text-muted">
                                @if($otherUser->role === 'employer')
                                    <span class="badge bg-success-subtle text-success">Employer</span>
                                @elseif($otherUser->role === 'seeker')
                                    <span class="badge bg-info-subtle text-info">Job Seeker</span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary">Admin</span>
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-three-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <form method="POST" action="{{ route('messages.delete-conversation', $otherUser->id) }}" 
                                  onsubmit="return confirm('Are you sure you want to delete this entire conversation?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-trash"></i> Delete Conversation
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body" style="height: 500px; overflow-y: auto;" id="messageContainer">
                    @forelse($messages as $message)
                        @php
                            $isCurrentUser = $message->sender_id === Auth::id();
                        @endphp
                        
                        <div class="mb-3 {{ $isCurrentUser ? 'text-end' : '' }}">
                            <div class="d-inline-block {{ $isCurrentUser ? 'bg-primary text-white' : 'bg-light' }} rounded p-3" 
                                 style="max-width: 70%;">
                                <p class="mb-1">{{ $message->message_content }}</p>
                                <small class="{{ $isCurrentUser ? 'text-white-50' : 'text-muted' }}">
                                    {{ $message->sent_at->format('M d, Y h:i A') }}
                                    @if($isCurrentUser && $message->read_at)
                                        <i class="bi bi-check2-all text-success"></i>
                                    @elseif($isCurrentUser)
                                        <i class="bi bi-check2"></i>
                                    @endif
                                </small>
                            </div>
                            
                            @if(!$isCurrentUser)
                                <!-- Delete option for received messages -->
                                <div class="d-inline-block ms-2">
                                    <form method="POST" action="{{ route('messages.destroy', $message->id) }}" 
                                          class="d-inline" 
                                          onsubmit="return confirm('Delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @else
                                <!-- Delete option for sent messages -->
                                <div class="d-inline-block me-2">
                                    <form method="POST" action="{{ route('messages.destroy', $message->id) }}" 
                                          class="d-inline" 
                                          onsubmit="return confirm('Delete this message?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-link text-danger p-0">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-5 text-muted">
                            <p>No messages yet. Start the conversation!</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Message Input Form -->
                <div class="card-footer bg-white border-top">
                    <form method="POST" action="{{ route('messages.send') }}">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ $otherUser->id }}">
                        
                        <div class="row g-2">
                            <div class="col">
                                <textarea 
                                    name="message_content" 
                                    class="form-control @error('message_content') is-invalid @enderror" 
                                    rows="2" 
                                    placeholder="Type your message here..."
                                    required></textarea>
                                @error('message_content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-auto d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-send"></i> Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-scroll to bottom of messages on page load
document.addEventListener('DOMContentLoaded', function() {
    var messageContainer = document.getElementById('messageContainer');
    if (messageContainer) {
        messageContainer.scrollTop = messageContainer.scrollHeight;
    }
});

// Auto-scroll after sending message
document.querySelector('form').addEventListener('submit', function() {
    setTimeout(function() {
        var messageContainer = document.getElementById('messageContainer');
        if (messageContainer) {
            messageContainer.scrollTop = messageContainer.scrollHeight;
        }
    }, 100);
});
</script>
@endsection
