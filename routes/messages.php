<?php

use App\Http\Controllers\MessageController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Messaging Routes
|--------------------------------------------------------------------------
| Routes for internal messaging system between employers and job seekers
*/

Route::middleware(['auth', 'verified'])->prefix('messages')->name('messages.')->group(function () {
    
    // Inbox - List all conversations
    Route::get('/', [MessageController::class, 'inbox'])->name('inbox');
    
    // View conversation with specific user (opens conversation or creates if doesn't exist)
    Route::get('/{userId}', [MessageController::class, 'show'])->name('show');
    
    // Send message
    Route::post('/send', [MessageController::class, 'send'])->name('send');
    
    // Mark message as read
    Route::patch('/{messageId}/read', [MessageController::class, 'markAsRead'])->name('read');
    
    // Delete single message (soft delete)
    Route::delete('/{messageId}', [MessageController::class, 'destroy'])->name('destroy');
    
    // Delete entire conversation
    Route::delete('/conversation/{userId}', [MessageController::class, 'deleteConversation'])->name('delete-conversation');
    
    // Get unread count (for AJAX/badge updates)
    Route::get('/api/unread-count', [MessageController::class, 'unreadCount'])->name('unread-count');
});
