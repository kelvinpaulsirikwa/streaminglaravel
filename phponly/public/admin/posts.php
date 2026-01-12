<?php
/**
 * Admin Posts Management - Pure PHP Version
 */

require_once __DIR__ . '/../index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts Management - Admin Dashboard</title>
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
                <li class="menu-item active">
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
                    <h1><i class="fas fa-images"></i> Posts Management</h1>
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

            <!-- Posts Content -->
            <div class="content-section">
                <!-- Search and Filters -->
                <div class="filters-section">
                    <div class="filter-row">
                        <div class="filter-group">
                            <label><i class="fas fa-search"></i> Search</label>
                            <input type="text" id="search-input" class="form-control" placeholder="Search posts...">
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-filter"></i> Media Type</label>
                            <select id="media-type-filter" class="form-control">
                                <option value="">All Types</option>
                                <option value="image">Image</option>
                                <option value="video">Video</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <label><i class="fas fa-check-circle"></i> PG Content</label>
                            <select id="pg-filter" class="form-control">
                                <option value="">All</option>
                                <option value="1">PG Only</option>
                                <option value="0">18+ Content</option>
                            </select>
                        </div>
                        <div class="filter-group">
                            <button class="btn btn-primary" onclick="searchPosts()">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Posts Table -->
                <div class="table-section">
                    <div class="table-header">
                        <h2><i class="fas fa-list"></i> Posts List</h2>
                        <div class="table-actions">
                            <button class="btn btn-success" onclick="exportPosts()">
                                <i class="fas fa-download"></i> Export
                            </button>
                            <button class="btn btn-primary" onclick="addPost()">
                                <i class="fas fa-plus"></i> Add Post
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Preview</th>
                                    <th>Superstar</th>
                                    <th>Media Type</th>
                                    <th>Description</th>
                                    <th>PG</th>
                                    <th>Disturbing</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="posts-table-body">
                                <tr>
                                    <td colspan="9" class="text-center">
                                        <div class="loading">Loading posts...</div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination-section">
                        <div class="pagination-info">
                            <span id="pagination-info">Showing 0 of 0 posts</span>
                        </div>
                        <div class="pagination-controls" id="pagination-controls">
                            <!-- Pagination will be inserted here -->
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Post Modal -->
    <div class="modal fade" id="postModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="postForm">
                        <input type="hidden" id="postId">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="user_id">Superstar</label>
                                    <select class="form-control" id="user_id" required>
                                        <option value="">Select Superstar</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="media_type">Media Type</label>
                                    <select class="form-control" id="media_type" required>
                                        <option value="image">Image</option>
                                        <option value="video">Video</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="resource_type">Resource Type</label>
                                    <select class="form-control" id="resource_type" required>
                                        <option value="upload">Upload</option>
                                        <option value="url">URL</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="resource_url_path">Resource URL/Path</label>
                                    <input type="text" class="form-control" id="resource_url_path" required>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" rows="4"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_pg">PG Content</label>
                                    <select class="form-control" id="is_pg" required>
                                        <option value="1">Yes (PG)</option>
                                        <option value="0">No (18+)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_disturbing">Disturbing Content</label>
                                    <select class="form-control" id="is_disturbing" required>
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="savePost()">
                        <i class="fas fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="posts.js"></script>
</body>
</html>
