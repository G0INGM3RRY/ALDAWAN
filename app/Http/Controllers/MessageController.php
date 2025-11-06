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
            ->with(['sender.employer', 'receiver.employer'])
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
                
                // Determine display name based on role and viewer
                $displayName = $otherUser->name;
                
                // If the other user is an employer, show company name instead
                if ($otherUser->role === 'employer' && $otherUser->employer) {
                    $displayName = $otherUser->employer->company_name;
                }
                
                // Count unread messages from this user
                $unreadCount = Message::where('sender_id', $otherUserId)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->where('is_deleted_by_receiver', false)
                    ->count();
                
                $conversationsMap[$otherUserId] = (object)[
                    'id' => $otherUser->id,
                    'name' => $displayName,
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
     * Only allows messaging between users who have interacted (employer-applicant relationship)
     */
    public function show($userId)
    {
        $currentUser = Auth::user();
        $otherUser = User::with('employer')->findOrFail($userId);
        
        // Prevent messaging yourself
        if ($currentUser->id == $userId) {
            return redirect()->route('messages.inbox')->with('error', 'You cannot message yourself.');
        }
        
        // Check if users have an existing relationship (application interaction)
        $hasRelationship = $this->checkUserRelationship($currentUser, $otherUser);
        
        // Check if they already have a conversation
        $hasConversation = Message::conversation($currentUser->id, $userId)->exists();
        
        // Only allow messaging if they have a relationship OR existing conversation
        if (!$hasRelationship && !$hasConversation) {
            return redirect()->route('messages.inbox')->with('error', 'You can only message users you have interacted with through job applications.');
        }
        
        // Get all messages in conversation
        $messages = Message::conversation($currentUser->id, $userId)
            ->where(function($query) use ($currentUser, $userId) {
                $query->where(function($q) use ($currentUser) {
                    $q->where('sender_id', $currentUser->id)
                      ->where('is_deleted_by_sender', false);
                })->orWhere(function($q) use ($currentUser) {
                    $q->where('receiver_id', $currentUser->id)
                      ->where('is_deleted_by_receiver', false);
                });
            })
            ->with(['sender', 'receiver'])
            ->orderBy('sent_at', 'asc')
            ->get();
        
        // Mark all unread messages from other user as read
        Message::where('sender_id', $userId)
            ->where('receiver_id', $currentUser->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        // Determine display name - use company name for employers
        $displayName = $otherUser->name;
        if ($otherUser->role === 'employer' && $otherUser->employer) {
            $displayName = $otherUser->employer->company_name;
        }
        
        return view('messages.conversation', compact('messages', 'otherUser', 'displayName'));
    }
    
    /**
     * Check if two users have a relationship through job applications
     */
    private function checkUserRelationship($user1, $user2)
    {
        // If user1 is employer, check if user2 has applied to their jobs
        if ($user1->role === 'employer') {
            $hasApplicant = \App\Models\JobApplication::whereHas('job', function($query) use ($user1) {
                $query->where('company_id', $user1->id);
            })->where('user_id', $user2->id)->exists();
            
            if ($hasApplicant) return true;
        }
        
        // If user1 is seeker, check if they applied to user2's jobs
        if ($user1->role === 'seeker') {
            $hasAppliedTo = \App\Models\JobApplication::whereHas('job', function($query) use ($user2) {
                $query->where('company_id', $user2->id);
            })->where('user_id', $user1->id)->exists();
            
            if ($hasAppliedTo) return true;
        }
        
        return false;
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

        $currentUser = Auth::user();
        $receiverId = $request->receiver_id;

        // Prevent sending messages to self
        if ($receiverId == $currentUser->id) {
            return back()->with('error', 'You cannot send messages to yourself.');
        }

        $receiver = User::findOrFail($receiverId);
        
        // Check if users have relationship or existing conversation
        $hasRelationship = $this->checkUserRelationship($currentUser, $receiver);
        $hasConversation = Message::conversation($currentUser->id, $receiverId)->exists();
        
        if (!$hasRelationship && !$hasConversation) {
            return back()->with('error', 'You can only message users you have interacted with through job applications.');
        }

        $message = Message::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $receiverId,
            'message_content' => $request->message_content,
            'sent_at' => now(),
            'message_type' => 'text'
        ]);

        return redirect()->route('messages.show', $receiverId)
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
