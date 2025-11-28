@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 fw-bold">
                    <i class="fas fa-bell me-2"></i>Notifications
                </h1>
                @if(Auth::user()->unreadNotificationsCount() > 0)
                    <form action="{{ route('notifications.markAllAsRead') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="fas fa-check-double me-1"></i>Mark All as Read
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @forelse($notifications as $notification)
                <div class="notification-item {{ $notification->isUnread() ? 'unread' : '' }} p-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-1">
                                @if($notification->isUnread())
                                    <span class="badge bg-primary me-2">New</span>
                                @endif
                                <h6 class="mb-0">{{ $notification->title }}</h6>
                            </div>
                            <p class="text-muted mb-2">{{ $notification->message }}</p>
                            <small class="text-muted">
                                <i class="far fa-clock me-1"></i>{{ $notification->created_at->diffForHumans() }}
                            </small>
                        </div>
                        <div class="ms-3">
                            <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" 
                                        onclick="return confirm('Delete this notification?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No notifications yet</p>
                </div>
            @endforelse
        </div>
    </div>

    @if($notifications->hasPages())
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endif
</div>

<style>
.notification-item.unread {
    background-color: #f8f9fa;
}
.notification-item:hover {
    background-color: #f1f3f5;
}
</style>
@endsection
