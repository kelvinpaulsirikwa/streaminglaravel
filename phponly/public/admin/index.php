<?php
/**
 * Admin Dashboard - Pure PHP Version
 */

require_once __DIR__ . '/../index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Rashid Backend</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="../css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-cog"></i> Admin Panel</h3>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-item active">
                    <a href="index.php">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="users.php">
                        <i class="fas fa-users"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="superstars.php">
                        <i class="fas fa-star"></i>
                        <span>Superstars</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="posts.php">
                        <i class="fas fa-images"></i>
                        <span>Posts</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="payments.php">
                        <i class="fas fa-credit-card"></i>
                        <span>Payments</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="subscriptions.php">
                        <i class="fas fa-heart"></i>
                        <span>Subscriptions</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="chats.php">
                        <i class="fas fa-comments"></i>
                        <span>Chats</span>
                    </a>
                </li>
                <li class="menu-item">
                    <a href="settings.php">
                        <i class="fas fa-cog"></i>
                        <span>Settings</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-left">
                    <h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
                </div>
                <div class="header-right">
                    <div class="user-menu">
                        <span><i class="fas fa-user-shield"></i> Admin</span>
                        <div class="dropdown-menu">
                            <a href="../index.html"><i class="fas fa-home"></i> View Site</a>
                            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="dashboard-content">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="total-users">0</h3>
                            <p>Total Users</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="total-superstars">0</h3>
                            <p>Superstars</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-images"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="total-posts">0</h3>
                            <p>Total Posts</p>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <div class="stat-info">
                            <h3 id="total-payments">0</h3>
                            <p>Payments</p>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="activity-section">
                    <h2><i class="fas fa-chart-line"></i> Recent Activity</h2>
                    
                    <div class="activity-grid">
                        <!-- Recent Users -->
                        <div class="activity-card">
                            <h3><i class="fas fa-user-plus"></i> Recent Users</h3>
                            <div class="activity-list" id="recent-users">
                                <div class="loading">Loading...</div>
                            </div>
                        </div>
                        
                        <!-- Recent Superstars -->
                        <div class="activity-card">
                            <h3><i class="fas fa-star"></i> Recent Superstars</h3>
                            <div class="activity-list" id="recent-superstars">
                                <div class="loading">Loading...</div>
                            </div>
                        </div>
                        
                        <!-- Recent Posts -->
                        <div class="activity-card">
                            <h3><i class="fas fa-image"></i> Recent Posts</h3>
                            <div class="activity-list" id="recent-posts">
                                <div class="loading">Loading...</div>
                            </div>
                        </div>
                        
                        <!-- Recent Payments -->
                        <div class="activity-card">
                            <h3><i class="fas fa-dollar-sign"></i> Recent Payments</h3>
                            <div class="activity-list" id="recent-payments">
                                <div class="loading">Loading...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Status -->
                <div class="status-section">
                    <h2><i class="fas fa-heartbeat"></i> System Status</h2>
                    
                    <div class="status-grid">
                        <div class="status-item">
                            <div class="status-indicator online"></div>
                            <div class="status-info">
                                <h4>API Server</h4>
                                <p>Online and responding</p>
                            </div>
                        </div>
                        
                        <div class="status-item">
                            <div class="status-indicator online"></div>
                            <div class="status-info">
                                <h4>Database</h4>
                                <p>Connected and operational</p>
                            </div>
                        </div>
                        
                        <div class="status-item">
                            <div class="status-indicator online"></div>
                            <div class="status-info">
                                <h4>File Storage</h4>
                                <p>Working normally</p>
                            </div>
                        </div>
                        
                        <div class="status-item">
                            <div class="status-indicator warning"></div>
                            <div class="status-info">
                                <h4>Memory Usage</h4>
                                <p>Moderate load</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="dashboard.js"></script>
</body>
</html>
