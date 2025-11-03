<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    /**
     * Display inbox with list of conversations
     */
    public function inbox()
    {
        $userId = Auth::id();
        
        // Get all messages involving the current user
        $allMessages = Message::where(function($query) use ($userId) {
                $query->where('sender_id', $userId)
                      ->where('is_deleted_by_sender', false);
            })
            ->orWhere(function($query) use ($userId) {
                $query->where('receiver_id', $userId)
                      ->where('is_deleted_by_receiver', false);
            })
            ->with(['sender', 'receiver'])
            ->orderBy('sent_at', 'desc')
            ->get();
        
        // Group messages by conversation partner
        $conversationsMap = [];
        
        foreach ($allMessages as $message) {
            // Determine the other user in the conversation
            $otherUserId = ($message->sender_id === $userId) ? $message->receiver_id : $message->sender_id;
            
            // If this conversation doesn't exist yet, add it
            if (!isset($conversationsMap[$otherUserId])) {
                $otherUser = ($message->sender_id === $userId) ? $message->receiver : $message->sender;
                
                // Count unread messages from this user
                $unreadCount = Message::where('sender_id', $otherUserId)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->where('is_deleted_by_receiver', false)
                    ->count();
                
                $conversationsMap[$otherUserId] = (object)[
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'email' => $otherUser->email,
                    'role' => $otherUser->role,
                    'last_message' => $message->message_content,
                    'last_message_time' => $message->sent_at,
                    'unread_count' => $unreadCount
                ];
            }
        }
        
        // Convert to collection and sort by last message time
        $conversations = collect($conversationsMap)->sortByDesc('last_message_time')->values();
        
        return view('messages.inbox', compact('conversations'));
    }

    /**
     * Display conversation with a specific user
     */
    public function show($userId)
    {
        $currentUser = Auth::id();
        $otherUser = User::findOrFail($userId);
        
        // Get all messages in conversation
        $messages = Message::conversation($currentUser, $userId)
            ->where(function($query) use ($currentUser, $userId) {
                $query->where(function($q) use ($currentUser) {
                    $q->where('sender_id', $currentUser)
                      ->where('is_deleted_by_sender', false);
                })->orWhere(function($q) use ($currentUser) {
                    $q->where('receiver_id', $currentUser)
                      ->where('is_deleted_by_receiver', false);
                });
            })
            ->with(['sender', 'receiver'])
            ->orderBy('sent_at', 'asc')
            ->get();
        
        // Mark all unread messages from other user as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUser)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        return view('messages.conversation', compact('messages', 'otherUser'));
    }

    /**
     * Send a new message
     */
    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message_content' => 'required|string|max:5000',
        ]);

        // Prevent sending messages to self
        if ($request->receiver_id == Auth::id()) {
            return back()->with('error', 'You cannot send messages to yourself.');
        }

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'message_content' => $request->message_content,
            'sent_at' => now(),
            'message_type' => 'text'
        ]);

        return redirect()->route('messages.show', $request->receiver_id)
            ->with('success', 'Message sent successfully!');
    }

    /**
     * Mark a message as read
     */
    public function markAsRead($messageId)
    {
        $message = Message::findOrFail($messageId);
        
        // Only receiver can mark as read
        if ($message->receiver_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        $message->markAsRead();
        
        return response()->json(['success' => true]);
    }

    /**
     * Soft delete a message (marks as deleted for current user)
     */
    public function destroy($messageId)
    {
        $message = Message::findOrFail($messageId);
        $userId = Auth::id();
        
        // Mark as deleted for the appropriate user
        if ($message->sender_id === $userId) {
            $message->update(['is_deleted_by_sender' => true]);
        } elseif ($message->receiver_id === $userId) {
            $message->update(['is_deleted_by_receiver' => true]);
        } else {
            abort(403, 'Unauthorized action.');
        }
        
        // If both users deleted it, permanently delete
        if ($message->is_deleted_by_sender && $message->is_deleted_by_receiver) {
            $message->delete();
        }
        
        return back()->with('success', 'Message deleted successfully!');
    }

    /**
     * Delete entire conversation with a user
     */
    public function deleteConversation($userId)
    {
        $currentUser = Auth::id();
        
        // Get all messages in conversation
        $messages = Message::conversation($currentUser, $userId)->get();
        
        foreach ($messages as $message) {
            if ($message->sender_id === $currentUser) {
                $message->update(['is_deleted_by_sender' => true]);
            } else {
                $message->update(['is_deleted_by_receiver' => true]);
            }
            
            // Permanently delete if both deleted
            if ($message->is_deleted_by_sender && $message->is_deleted_by_receiver) {
                $message->delete();
            }
        }
        
        return redirect()->route('messages.inbox')
            ->with('success', 'Conversation deleted successfully!');
    }

    /**
     * Get unread message count for current user
     */
    public function unreadCount()
    {
        $count = Message::where('receiver_id', Auth::id())
            ->whereNull('read_at')
            ->where('is_deleted_by_receiver', false)
            ->count();
        
        return response()->json(['count' => $count]);
    }

    /**
     * Compose new message (show form to select user and compose)
     */
    public function compose()
    {
        $currentUser = Auth::user();
        
        // Get potential recipients based on user role
        if ($currentUser->role === 'employer') {
            // Employers can message job seekers
            $users = User::where('role', 'seeker')
                ->where('id', '!=', $currentUser->id)
                ->orderBy('name')
                ->get();
        } elseif ($currentUser->role === 'seeker') {
            // Job seekers can message employers
            $users = User::where('role', 'employer')
                ->where('id', '!=', $currentUser->id)
                ->orderBy('name')
                ->get();
        } else {
            // Admins can message anyone
            $users = User::where('id', '!=', $currentUser->id)
                ->orderBy('name')
                ->get();
        }
        
        return view('messages.compose', compact('users'));
    }
}
