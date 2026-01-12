<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserApi\UserApiLogin;
use App\Http\Controllers\UserApi\SubscriptionController;
use App\Http\Controllers\UserApi\UserSuperStarController;
use App\Http\Controllers\UserApi\ChatController;
use App\Http\Controllers\UserApi\PaymentController;
use App\Http\Controllers\UserApi\PaymentHistoryController;

/**
 * Google OAuth and User API Routes
 * Base URL: /api/user
 */

// Public routes (no authentication required)
Route::post('/google-login', [UserApiLogin::class, 'googleLogin'])->name('api.user.google-login');

// Protected routes (Sanctum authentication required)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [UserApiLogin::class, 'getAuthUser'])->name('api.user.me');
    Route::post('/logout', [UserApiLogin::class, 'logout'])->name('api.user.logout');
    
    // Subscription routes
    Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('api.user.subscriptions.index');
    Route::post('/subscriptions', [SubscriptionController::class, 'store'])->name('api.user.subscriptions.store');
    Route::delete('/subscriptions/{superstarId}', [SubscriptionController::class, 'destroy'])->name('api.user.subscriptions.destroy');
    // Superstars: list, details, and posts for authenticated users
    Route::get('/superstars', [UserSuperStarController::class, 'index'])->name('api.user.superstars.index');
    Route::get('/superstars/{id}', [UserSuperStarController::class, 'show'])->name('api.user.superstars.show');
    Route::get('/superstars/{id}/posts', [UserSuperStarController::class, 'posts'])->name('api.user.superstars.posts');
    
    // Chat routes
    Route::prefix('chat')->group(function () {
        Route::get('/conversations', [ChatController::class, 'getConversations']);
        Route::get('/unread-count', [ChatController::class, 'getUnreadCount']);
        Route::post('/start/{superstarId}', [ChatController::class, 'startChat']);
        Route::get('/messages/{conversationId}', [ChatController::class, 'getMessages']);
        Route::post('/send/{conversationId}', [ChatController::class, 'sendMessage']);
        Route::delete('/message/{messageId}', [ChatController::class, 'deleteMessage']);
    });
    
    // Payment routes
    Route::prefix('payments')->group(function () {
        Route::post('/process', [PaymentController::class, 'processPayment']);
        Route::get('/history', [PaymentController::class, 'getPaymentHistory']);
        Route::get('/{paymentId}', [PaymentController::class, 'getPaymentDetails']);
    });
    
    // Payment History routes
    Route::prefix('payment-history')->group(function () {
        Route::get('/user', [PaymentHistoryController::class, 'getUserPayments']);
        Route::get('/superstar/{superstarId}', [PaymentHistoryController::class, 'getPaymentsBySuperstar']);
        Route::get('/transaction/{transactionReference}', [PaymentHistoryController::class, 'getTransactionDetails']);
    });
}); 