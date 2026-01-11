<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * API Routes
 * Prefix: /api
 */

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});

// User API routes
Route::prefix('user')->group(base_path('routes/apiUser.php'));

// SuperStar API routes
Route::prefix('superstar')->group(base_path('routes/apiSuperStar.php'));
