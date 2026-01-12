<?php
/**
 * Application Configuration - Pure PHP Version
 */

return [
    'name' => 'Rashid Backend API',
    'version' => '1.0.0',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'timezone' => 'UTC',
    'jwt_secret' => $_ENV['JWT_SECRET'] ?? 'your-secret-key-here',
    'jwt_expiration' => 3600, // 1 hour
    'upload_path' => __DIR__ . '/../public/uploads/',
    'max_file_size' => 10 * 1024 * 1024, // 10MB
];
