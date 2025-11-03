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
        <div class="col-md-8">
            <h1 class="h3 fw-bold text-primary">
                @if(Auth::user()->role === 'employer')
                    <span class="text-success">Messages</span>
                @elseif(Auth::user()->role === 'seeker')
                    <span class="text-info">Messages</span>
                @else
                    <span class="text-primary">Messages</span>
                @endif
            </h1>
            <p class="text-muted">Your conversations with other users</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('messages.compose') }}" class="btn btn-primary">
                <i class="bi bi-pencil-square"></i> New Message
            </a>
        </div>
    </div>

    <!-- Conversations List -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    @forelse($conversations as $conversation)
                        <a href="{{ route('messages.show', $conversation->id) }}" 
                           class="text-decoration-none">
                            <div class="border-bottom p-3 conversation-item {{ $conversation->unread_count > 0 ? 'bg-light' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex align-items-center mb-1">
                                            <!-- User Avatar/Icon -->
                                            <div class="me-3">
                                                @if($conversation->role === 'employer')
                                                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; font-size: 24px;">
                                                        🏢
                                                    </div>
                                                @elseif($conversation->role === 'seeker')
                                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; font-size: 24px;">
                                                        👤
                                                    </div>
                                                @else
                                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 50px; height: 50px; font-size: 24px;">
                                                        👨‍💼
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="flex-grow-1">
                                                <h6 class="mb-0 fw-bold text-dark">
                                                    {{ $conversation->name }}
                                                    @if($conversation->unread_count > 0)
                                                        <span class="badge bg-danger rounded-pill ms-2">
                                                            {{ $conversation->unread_count }}
                                                        </span>
                                                    @endif
                                                </h6>
                                                <small class="text-muted">
                                                    @if($conversation->role === 'employer')
                                                        <span class="badge bg-success-subtle text-success">Employer</span>
                                                    @elseif($conversation->role === 'seeker')
                                                        <span class="badge bg-info-subtle text-info">Job Seeker</span>
                                                    @else
                                                        <span class="badge bg-primary-subtle text-primary">Admin</span>
                                                    @endif
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <!-- Last Message Preview -->
                                        <div class="ms-5 ps-3">
                                            <p class="mb-1 text-muted small {{ $conversation->unread_count > 0 ? 'fw-bold' : '' }}">
                                                {{ Str::limit($conversation->last_message, 80) }}
                                            </p>
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($conversation->last_message_time)->diffForHumans() }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <!-- Arrow Icon -->
                                    <div class="ms-3">
                                        <i class="bi bi-chevron-right text-muted"></i>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <!-- No Messages -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <div class="text-muted display-1" style="opacity: 0.3;">💬</div>
                            </div>
                            <h5 class="text-muted">No messages yet</h5>
                            <p class="text-muted">Start a conversation by sending a new message.</p>
                            <a href="{{ route('messages.compose') }}" class="btn btn-primary mt-3">
                                <i class="bi bi-pencil-square"></i> Compose Message
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.conversation-item {
    transition: background-color 0.2s ease;
}

.conversation-item:hover {
    background-color: #f8f9fa !important;
}
</style>
@endsection
