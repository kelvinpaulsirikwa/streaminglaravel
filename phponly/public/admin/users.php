<?php
/**
 * Admin Users Management - Pure PHP Version
 */

require_once __DIR__ . '/../index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Management - Admin Dashboard</title>
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
                <li class="menu-item active">
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
                    <h1><i class="fas fa-users"></i> Users Management</h1>
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

            <!-- Users Content -->
            <div class="content-section">
                <!-- Search and Filters -->
                <div class="filters-section">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><i class="fas fa-search"></i> Search</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search users...">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-filter"></i> Role</label>
                            <select id="role-filter" class="form-control">
                                <option value="">All Roles</option>
                                <option value="user">User</option>
                                <option value="superstar">Superstar</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-check-circle"></i> Status</label>
                            <select id="status-filter" class="form-control">
                                <option value="">All Status</option>
                                <option value="1">Verified</option>
                                <option value="0">Not Verified</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <button class="btn btn-primary" onclick="searchUsers()">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Users Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2><i class="fas fa-list"></i> Users List</h2>
                        <div class="table-actions">
                            <button class="btn btn-success" onclick="exportUsers()">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <button class="btn btn-primary" onclick="addUser()">
                                <i class="fas fa-plus"></i> Add User
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Username</th>
                                    <th>Role</th>
                                    <th>Verified</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="users-table-body">
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="loading">Loading users...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-section">
                        <div class="pagination-info">
                            <span id="pagination-info">Showing 0 of 0 users</span>
                        </div>
                        <div class="pagination-controls" id="pagination-controls">
                            <!-- Pagination will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- User Modal -->
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="userId">
                        
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" id="username" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="role">Role</label>
                            <select class="form-control" id="role" required>
                                <option value="user">User</option>
                                <option value="superstar">Superstar</option>
                                <option value="admin">Admin</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="is_verified">Verified</label>
                            <select class="form-control" id="is_verified">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="is_blocked">Blocked</label>
                            <select class="form-control" id="is_blocked">
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveUser()">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="users.js"></script>
</body>
</html>
