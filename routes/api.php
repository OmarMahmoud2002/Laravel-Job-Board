<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Get users by role
Route::middleware('auth')->get('/users', [UserController::class, 'getUsersByRole']);

// Chat API Routes
Route::middleware('auth')->prefix('chat')->group(function () {
    // Get all conversations for the authenticated user
    Route::get('/conversations', [ChatController::class, 'getConversations'])->name('api.chat.conversations.index');

    // Get messages for a specific conversation
    Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'getMessages'])->name('api.chat.conversations.messages');

    // Mark all messages in a conversation as read
    Route::post('/conversations/{conversationId}/read', [ChatController::class, 'markAsRead'])->name('api.chat.conversations.read');

    // Send a new message
    Route::post('/messages', [ChatController::class, 'sendMessage'])->name('api.chat.messages');

    // Send typing indicator
    Route::post('/typing', [ChatController::class, 'sendTypingIndicator'])->name('api.chat.typing');

    // Start a new conversation
    Route::post('/conversations', [ChatController::class, 'startConversation'])->name('api.chat.conversations');
});
