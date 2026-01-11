@extends('adminpages.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <h1 class="page-title">Dashboard</h1>
</div>

<!-- Welcome Message -->
<div class="welcome-message">
    <h2>Welcome back, {{ auth()->user()->name ?? 'Admin' }}!</h2>
    <p>{{ config('site.owner.welcome_note') }}</p>
</div>

<!-- Statistics Cards -->
<div class="dashboard-grid">
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Users</span>
            <div class="stat-card-icon" style="background: rgba(102, 126, 234, 0.1); color: #667eea;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">0</div>
        <div class="stat-card-change">+0% from last month</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Active Streams</span>
            <div class="stat-card-icon" style="background: rgba(40, 167, 69, 0.1); color: #28a745;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="23 7 16 12 23 17 23 7"></polygon>
                    <rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">0</div>
        <div class="stat-card-change">+0% from last month</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Total Views</span>
            <div class="stat-card-icon" style="background: rgba(255, 193, 7, 0.1); color: #ffc107;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                    <circle cx="12" cy="12" r="3"></circle>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">0</div>
        <div class="stat-card-change">+0% from last month</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Revenue</span>
            <div class="stat-card-icon" style="background: rgba(220, 53, 69, 0.1); color: #dc3545;">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="12" y1="1" x2="12" y2="23"></line>
                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path>
                </svg>
            </div>
        </div>
        <div class="stat-card-value">$0</div>
        <div class="stat-card-change">+0% from last month</div>
    </div>
</div>

<!-- Content Section -->
<div class="content-section">
    <h2 class="section-title">Recent Activity</h2>
    <p style="color: #666;">No recent activity to display.</p>
</div>

<style>
    .page-header {
        margin-bottom: 24px;
    }
    
    .page-title {
        font-size: 28px;
        font-weight: 600;
        color: var(--text-primary);
    }
    
    .welcome-message {
        background: linear-gradient(135deg, var(--primary-color) 0%, #764ba2 100%);
        color: #ffffff;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
    }
    
    .welcome-message h2 {
        font-size: 24px;
        margin-bottom: 10px;
    }
    
    .welcome-message p {
        opacity: 0.9;
        line-height: 1.6;
    }
    
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    
    .stat-card {
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        transition: all 0.2s;
    }
    
    .stat-card:hover {
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .stat-card-title {
        font-size: 14px;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }
    
    .stat-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .stat-card-value {
        font-size: 32px;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 5px;
    }
    
    .stat-card-change {
        font-size: 12px;
        color: #28a745;
        font-weight: 500;
    }
    
    .content-section {
        background: #ffffff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
    }
    
    .section-title {
        font-size: 20px;
        color: var(--text-primary);
        margin-bottom: 20px;
        font-weight: 600;
    }
    
    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        
        .welcome-message {
            padding: 20px;
        }
        
        .welcome-message h2 {
            font-size: 20px;
        }
        
        .content-section {
            padding: 20px;
        }
    }
</style>
@endsection
