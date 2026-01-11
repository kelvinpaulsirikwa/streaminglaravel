<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperStar\SuperStarAuth;
use App\Http\Controllers\SuperStar\SuperstarPostController;
use App\Http\Controllers\SuperStar\ChatController;
use App\Http\Controllers\SuperStar\PaymentController;

/**
 * SuperStar API Routes
 * Base URL: /api/superstar
 */

// Public routes (no authentication required)
Route::post('/login', [SuperStarAuth::class, 'login'])->name('api.superstar.login');

// Protected routes (Sanctum authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [SuperStarAuth::class, 'me'])->name('api.superstar.me');
    Route::post('/logout', [SuperStarAuth::class, 'logout'])->name('api.superstar.logout');
    Route::put('/profile', [SuperStarAuth::class, 'updateProfile'])->name('api.superstar.profile.update');
    Route::post('/change-password', [SuperStarAuth::class, 'changePassword'])->name('api.superstar.password.change');
    
    // SuperStar Posts CRUD routes
    Route::prefix('posts')->group(function () {
        Route::get('/', [SuperstarPostController::class, 'index'])->name('api.superstar.posts.index');
        Route::post('/', [SuperstarPostController::class, 'store'])->name('api.superstar.posts.store');
        Route::get('/{id}', [SuperstarPostController::class, 'show'])->name('api.superstar.posts.show');
        Route::put('/{id}', [SuperstarPostController::class, 'update'])->name('api.superstar.posts.update');
        Route::delete('/{id}', [SuperstarPostController::class, 'destroy'])->name('api.superstar.posts.destroy');
    });
    
    // SuperStar Chat routes
    Route::prefix('chat')->group(function () {
        Route::get('/conversations', [ChatController::class, 'getConversations']);
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount']);
        Route::get('/messages/{conversationId}', [ChatController::class, 'getMessages']);
        Route::post('/send/{conversationId}', [ChatController::class, 'sendMessage']);
        Route::post('/read/{conversationId}', [ChatController::class, 'markMessagesAsRead']);
        Route::put('/conversation/{conversationId}/status', [ChatController::class, 'updateConversationStatus']);
        Route::delete('/message/{messageId}', [ChatController::class, 'deleteMessage']);
    });
    
    // SuperStar Payment routes
    Route::prefix('payments')->group(function () {
        Route::get('/history', [PaymentController::class, 'getPaymentHistory']);
        Route::get('/system-revenue', [PaymentController::class, 'getSystemRevenue']);
        Route::get('/user/{userId}', [PaymentController::class, 'getUserPaymentHistory']);
    });
});