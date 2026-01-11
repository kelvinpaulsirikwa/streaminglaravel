<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SuperstarController;
use App\Http\Controllers\Admin\UserGoogleController;
use App\Http\Controllers\Admin\FinanceController;

Route::get('/', function () {
    return view('welcome');
});

// Admin Authentication Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [LoginController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [LoginController::class, 'logout'])->name('admin.logout');
    
    // Protected Admin Routes
    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::resource('superstars', SuperstarController::class, ['names' => [
            'index' => 'admin.superstars.index',
            'create' => 'admin.superstars.create',
            'store' => 'admin.superstars.store',
            'show' => 'admin.superstars.show',
            'edit' => 'admin.superstars.edit',
            'update' => 'admin.superstars.update',
            'destroy' => 'admin.superstars.destroy',
        ]]);
        
        Route::get('/usergoogles', [UserGoogleController::class, 'index'])->name('admin.usergoogles.index');
        Route::get('/usergoogles/{id}', [UserGoogleController::class, 'show'])->name('admin.usergoogles.show');
        
        Route::get('/finance', [FinanceController::class, 'index'])->name('admin.finance.index');
        Route::get('/finance/{payment}', [FinanceController::class, 'show'])->name('admin.finance.show');
        Route::get('/finance/statistics', [FinanceController::class, 'statistics'])->name('admin.finance.statistics');
    });
});

// Profile Routes (Protected)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'changePassword'])->name('profile.password');
});
