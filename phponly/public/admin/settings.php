<?php
/**
 * Admin Settings - Pure PHP Version
 */

require_once __DIR__ . '/../index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - Admin Dashboard</title>
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
                <li class="menu-item">
                    <a href="chats.php">
                        <i class="fas fa-comments"></i>
                        <span>Chats</span>
                    </a>
                </li>
                <li class="menu-item active">
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
                    <h1><i class="fas fa-cog"></i> System Settings</h1>
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

            <!-- Settings Content -->
            <div class="content-section">
                <div class="settings-grid">
                    <!-- General Settings -->
                    <div class="settings-card">
                        <h3><i class="fas fa-cogs"></i> General Settings</h3>
                        <form id="generalSettings">
                            <div class="form-group">
                                <label for="site_name">Site Name</label>
                                <input type="text" class="form-control" id="site_name" value="Rashid Backend API">
                            </div>
                            <div class="form-group">
                                <label for="site_description">Site Description</label>
                                <textarea class="form-control" id="site_description" rows="3">Complete Laravel to Pure PHP conversion with full functionality</textarea>
                            </div>
                            <div class="form-group">
                                <label for="admin_email">Admin Email</label>
                                <input type="email" class="form-control" id="admin_email" value="admin@example.com">
                            </div>
                            <div class="form-group">
                                <label for="maintenance_mode">Maintenance Mode</label>
                                <select class="form-control" id="maintenance_mode">
                                    <option value="0">Disabled</option>
                                    <option value="1">Enabled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="saveGeneralSettings()">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- API Settings -->
                    <div class="settings-card">
                        <h3><i class="fas fa-code"></i> API Settings</h3>
                        <form id="apiSettings">
                            <div class="form-group">
                                <label for="api_rate_limit">API Rate Limit (requests/minute)</label>
                                <input type="number" class="form-control" id="api_rate_limit" value="60" min="1">
                            </div>
                            <div class="form-group">
                                <label for="jwt_expiration">JWT Expiration (hours)</label>
                                <input type="number" class="form-control" id="jwt_expiration" value="24" min="1">
                            </div>
                            <div class="form-group">
                                <label for="enable_api_docs">Enable API Documentation</label>
                                <select class="form-control" id="enable_api_docs">
                                    <option value="1">Enabled</option>
                                    <option value="0">Disabled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="saveApiSettings()">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Security Settings -->
                    <div class="settings-card">
                        <h3><i class="fas fa-shield-alt"></i> Security Settings</h3>
                        <form id="securitySettings">
                            <div class="form-group">
                                <label for="require_email_verification">Require Email Verification</label>
                                <select class="form-control" id="require_email_verification">
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="session_timeout">Session Timeout (minutes)</label>
                                <input type="number" class="form-control" id="session_timeout" value="30" min="5">
                            </div>
                            <div class="form-group">
                                <label for="max_login_attempts">Max Login Attempts</label>
                                <input type="number" class="form-control" id="max_login_attempts" value="5" min="1">
                            </div>
                            <div class="form-group">
                                <label for="enable_captcha">Enable CAPTCHA</label>
                                <select class="form-control" id="enable_captcha">
                                    <option value="0">Disabled</option>
                                    <option value="1">Enabled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="saveSecuritySettings()">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Email Settings -->
                    <div class="settings-card">
                        <h3><i class="fas fa-envelope"></i> Email Settings</h3>
                        <form id="emailSettings">
                            <div class="form-group">
                                <label for="smtp_host">SMTP Host</label>
                                <input type="text" class="form-control" id="smtp_host" placeholder="smtp.example.com">
                            </div>
                            <div class="form-group">
                                <label for="smtp_port">SMTP Port</label>
                                <input type="number" class="form-control" id="smtp_port" value="587">
                            </div>
                            <div class="form-group">
                                <label for="smtp_username">SMTP Username</label>
                                <input type="text" class="form-control" id="smtp_username">
                            </div>
                            <div class="form-group">
                                <label for="smtp_password">SMTP Password</label>
                                <input type="password" class="form-control" id="smtp_password">
                            </div>
                            <div class="form-group">
                                <label for="smtp_encryption">SMTP Encryption</label>
                                <select class="form-control" id="smtp_encryption">
                                    <option value="tls">TLS</option>
                                    <option value="ssl">SSL</option>
                                    <option value="none">None</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="saveEmailSettings()">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Storage Settings -->
                    <div class="settings-card">
                        <h3><i class="fas fa-database"></i> Storage Settings</h3>
                        <form id="storageSettings">
                            <div class="form-group">
                                <label for="max_file_size">Max File Size (MB)</label>
                                <input type="number" class="form-control" id="max_file_size" value="10" min="1">
                            </div>
                            <div class="form-group">
                                <label for="allowed_file_types">Allowed File Types</label>
                                <input type="text" class="form-control" id="allowed_file_types" value="jpg,jpeg,png,gif,mp4,mp3,pdf">
                            </div>
                            <div class="form-group">
                                <label for="storage_path">Storage Path</label>
                                <input type="text" class="form-control" id="storage_path" value="/storage/uploads">
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-primary" onclick="saveStorageSettings()">
                                    <i class="fas fa-save"></i> Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- System Information -->
                <div class="settings-card">
                    <h3><i class="fas fa-info-circle"></i> System Information</h3>
                    <div class="system-info">
                        <div class="info-item">
                            <label>PHP Version:</label>
                            <span><?php echo PHP_VERSION; ?></span>
                        </div>
                        <div class="info-item">
                            <label>Server Software:</label>
                            <span><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown'; ?></span>
                        </div>
                        <div class="info-item">
                            <label>Database:</label>
                            <span>MySQL</span>
                        </div>
                        <div class="info-item">
                            <label>Memory Limit:</label>
                            <span><?php echo ini_get('memory_limit'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Max Upload Size:</label>
                            <span><?php echo ini_get('upload_max_filesize'); ?></span>
                        </div>
                        <div class="info-item">
                            <label>Timezone:</label>
                            <span><?php echo date_default_timezone_get(); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="settings.js"></script>
</body>
</html>
