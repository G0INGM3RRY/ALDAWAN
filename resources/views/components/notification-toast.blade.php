<!-- Toast Container for Notifications -->
<div class="position-fixed top-0 end-0 p-3" style="z-index: 11000;">
    <div id="notification-toast-container">
        @php
            $unreadNotifications = Auth::user()->notifications()->whereNull('read_at')->latest()->take(3)->get();
        @endphp
        
        @foreach($unreadNotifications as $notification)
            <div class="toast show mb-2" role="alert" aria-live="assertive" aria-atomic="true" data-notification-id="{{ $notification->id }}">
                <div class="toast-header bg-primary text-white">
                    <i class="fas fa-bell me-2"></i>
                    <strong class="me-auto">{{ $notification->title }}</strong>
                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
                <div class="toast-body">
                    {{ Str::limit($notification->message, 100) }}
                    @if($notification->action_url)
                        <div class="mt-2">
                            <a href="{{ $notification->action_url }}" class="btn btn-sm btn-primary" onclick="markNotificationAsRead({{ $notification->id }})">
                                View Details
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<script>
    // Auto-hide toasts after 10 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach((toastElement) => {
            const toast = new bootstrap.Toast(toastElement, {
                autohide: true,
                delay: 10000
            });
            
            // Auto-hide after showing
            setTimeout(() => {
                toast.hide();
            }, 10000);
        });
    });

    function markNotificationAsRead(notificationId) {
        fetch(`/notifications/${notificationId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });
    }
</script>

<style>
    .toast {
        min-width: 300px;
        max-width: 400px;
    }
</style>
