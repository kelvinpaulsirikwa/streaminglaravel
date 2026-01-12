<?php
/**
 * Admin Superstars Management - Pure PHP Version
 */

require_once __DIR__ . '/../index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Superstars Management - Admin Dashboard</title>
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
                <li class="menu-item active">
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
                    <h1><i class="fas fa-star"></i> Superstars Management</h1>
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

            <!-- Superstars Content -->
            <div class="content-section">
                <!-- Search and Filters -->
                <div class="filters-section">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><i class="fas fa-search"></i> Search</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search superstars...">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-filter"></i> Status</label>
                            <select id="status-filter" class="form-control">
                                <option value="">All Status</option>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="suspended">Suspended</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-check-circle"></i> Available</label>
                            <select id="available-filter" class="form-control">
                                <option value="">All</option>
                                <option value="1">Available</option>
                                <option value="0">Unavailable</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <button class="btn btn-primary" onclick="searchSuperstars()">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Superstars Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2><i class="fas fa-list"></i> Superstars List</h2>
                        <div class="table-actions">
                            <button class="btn btn-success" onclick="exportSuperstars()">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <button class="btn btn-primary" onclick="addSuperstar()">
                                <i class="fas fa-plus"></i> Add Superstar
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Display Name</th>
                                    <th>User</th>
                                    <th>Price/Hour</th>
                                    <th>Rating</th>
                                    <th>Followers</th>
                                    <th>Status</th>
                                    <th>Available</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="superstars-table-body">
                                <tr>
                                    <td colspan="10" class="text-center">
                                        <div class="loading">Loading superstars...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-section">
                        <div class="pagination-info">
                            <span id="pagination-info">Showing 0 of 0 superstars</span>
                        </div>
                        <div class="pagination-controls" id="pagination-controls">
                            <!-- Pagination will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Superstar Modal -->
    <div class="modal fade" id="superstarModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Superstar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="superstarForm">
                        <input type="hidden" id="superstarId">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="display_name">Display Name</label>
                                    <input type="text" class="form-control" id="display_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">User</label>
                                    <select class="form-control" id="user_id" required>
                                        <option value="">Select User</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea class="form-control" id="bio" rows="4"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="price_per_hour">Price per Hour ($)</label>
                                    <input type="number" class="form-control" id="price_per_hour" step="0.01" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rating">Rating</label>
                                    <input type="number" class="form-control" id="rating" step="0.1" min="0" max="5">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="total_followers">Total Followers</label>
                                    <input type="number" class="form-control" id="total_followers" min="0">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select class="form-control" id="status" required>
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="suspended">Suspended</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="is_available">Available</label>
                                    <select class="form-control" id="is_available" required>
                                        <option value="1">Available</option>
                                        <option value="0">Unavailable</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveSuperstar()">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="superstars.js"></script>
</body>
</html>
