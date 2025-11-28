<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display all notifications
     */
    public function index()
    {
        $notifications = auth()->user()->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->markAsRead();

        if ($notification->action_url) {
            return redirect($notification->action_url);
        }

        return redirect()->back();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('user_id', auth()->id())
            ->findOrFail($id);

        $notification->delete();

        return redirect()->back()->with('success', 'Notification deleted');
    }
}
