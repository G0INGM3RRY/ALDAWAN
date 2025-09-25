<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'message_content',
        'sent_at',
        'read_at',
        'is_deleted_by_sender',
        'is_deleted_by_receiver',
        'message_type'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'read_at' => 'datetime',
        'is_deleted_by_sender' => 'boolean',
        'is_deleted_by_receiver' => 'boolean',
    ];

    /**
     * The user who sent this message
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * The user who received this message
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Mark message as read
     */
    public function markAsRead(): void
    {
        $this->update(['read_at' => now()]);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    /**
     * Scope for conversation between two users
     */
    public function scopeConversation($query, $user1Id, $user2Id)
    {
        return $query->where(function ($q) use ($user1Id, $user2Id) {
            $q->where(function ($subQ) use ($user1Id, $user2Id) {
                $subQ->where('sender_id', $user1Id)->where('receiver_id', $user2Id);
            })->orWhere(function ($subQ) use ($user1Id, $user2Id) {
                $subQ->where('sender_id', $user2Id)->where('receiver_id', $user1Id);
            });
        });
    }
}
