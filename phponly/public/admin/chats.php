<?php
/**
 * Admin Chats Management - Pure PHP Version
 */

require_once __DIR__ . '/../index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats Management - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-header">
                <h3><i class="fas fa-cog"></i> Admin Panel</h3>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-item">
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
                <li class="menu-item active">
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
                    <h1><i class="fas fa-comments"></i> Chats Management</h1>
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

            <!-- Chats Content -->
            <div class="content-section">
                <!-- Search and Filters -->
                <div class="filters-section">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><i class="fas fa-search"></i> Search</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search chats...">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-filter"></i> Status</label>
                            <select id="status-filter" class="form-control">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="archived">Archived</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-star"></i> Superstar</label>
                            <select id="superstar-filter" class="form-control">
                                <option value="">All Superstars</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <button class="btn btn-primary" onclick="searchChats()">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Chats Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2><i class="fas fa-list"></i> Chats List</h2>
                        <div class="table-actions">
                            <button class="btn btn-success" onclick="exportChats()">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <div class="stats-summary">
                                <span class="stat-item">
                                    <i class="fas fa-comments"></i>
                                    Total: <strong id="total-chats">0</strong>
                                </span>
                                <span class="stat-item">
                                    <i class="fas fa-clock"></i>
                                    Active: <strong id="active-chats">0</strong>
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Superstar</th>
                                    <th>Status</th>
                                    <th>Messages</th>
                                    <th>Duration</th>
                                    <th>Started</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="chats-table-body">
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="loading">Loading chats...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-section">
                        <div class="pagination-info">
                            <span id="pagination-info">Showing 0 of 0 chats</span>
                        </div>
                        <div class="pagination-controls" id="pagination-controls">
                            <!-- Pagination will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Chat Details Modal -->
    <div class="modal fade" id="chatModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Chat Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body" id="chat-details">
                    <!-- Chat details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="chats.js"></script>
</body>
</html>
