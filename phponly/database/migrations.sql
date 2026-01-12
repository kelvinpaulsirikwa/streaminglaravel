-- Rashid Backend API - Database Migration Script
-- Pure PHP Version Database Schema

-- Create database if not exists
CREATE DATABASE IF NOT EXISTS rashid_backend;
USE rashid_backend;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'superstar', 'admin') DEFAULT 'user',
    is_verified BOOLEAN DEFAULT FALSE,
    is_blocked BOOLEAN DEFAULT FALSE,
    profile_image TEXT,
    bio TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Superstars table
CREATE TABLE IF NOT EXISTS superstars (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    display_name VARCHAR(255) NOT NULL,
    bio TEXT,
    price_per_hour DECIMAL(10,2) DEFAULT 0.00,
    is_available BOOLEAN DEFAULT TRUE,
    rating DECIMAL(3,2) DEFAULT 0.0,
    total_followers INT DEFAULT 0,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Superstar Posts table
CREATE TABLE IF NOT EXISTS superstar_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    media_type ENUM('image', 'video') NOT NULL,
    resource_type ENUM('upload', 'url') NOT NULL,
    resource_url_path VARCHAR(500) NOT NULL,
    description TEXT,
    is_pg BOOLEAN DEFAULT FALSE,
    is_disturbing BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Subscriptions table
CREATE TABLE IF NOT EXISTS subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    superstar_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (superstar_id) REFERENCES superstars(id) ON DELETE CASCADE,
    UNIQUE KEY unique_subscription (user_id, superstar_id)
);

-- Conversations table
CREATE TABLE IF NOT EXISTS conversations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_google_id INT NOT NULL,
    superstar_id INT NOT NULL,
    status ENUM('active', 'inactive', 'archived') DEFAULT 'active',
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_google_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (superstar_id) REFERENCES superstars(id) ON DELETE CASCADE
);

-- Messages table
CREATE TABLE IF NOT EXISTS messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT NOT NULL,
    sender_type ENUM('user', 'superstar') NOT NULL,
    sender_id INT NOT NULL,
    message_type ENUM('text', 'image', 'video', 'file') NOT NULL,
    message TEXT,
    file_path VARCHAR(500),
    file_name VARCHAR(255),
    file_size INT,
    is_read BOOLEAN DEFAULT FALSE,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE
);

-- Chat Sessions table
CREATE TABLE IF NOT EXISTS chat_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    conversation_id INT NOT NULL,
    total_minutes INT DEFAULT 0,
    price_per_minute DECIMAL(10,2) DEFAULT 0.00,
    total_amount DECIMAL(10,2) DEFAULT 0.00,
    status ENUM('ongoing', 'completed', 'cancelled') DEFAULT 'ongoing',
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    ended_at TIMESTAMP NULL,
    session_message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (conversation_id) REFERENCES conversations(id) ON DELETE CASCADE
);

-- Payments table
CREATE TABLE IF NOT EXISTS payments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_google_id INT NOT NULL,
    superstar_id INT NOT NULL,
    chat_sessions_id INT,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_method ENUM('wallet', 'card', 'mobile_money') NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') DEFAULT 'pending',
    transaction_reference VARCHAR(255) UNIQUE NOT NULL,
    paid_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_google_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (superstar_id) REFERENCES superstars(id) ON DELETE CASCADE,
    FOREIGN KEY (chat_sessions_id) REFERENCES chat_sessions(id) ON DELETE CASCADE
);

-- Payment Breakdown table
CREATE TABLE IF NOT EXISTS payment_breakdowns (
    id INT AUTO_INCREMENT PRIMARY KEY,
    payment_id INT NOT NULL,
    superstar_amount DECIMAL(10,2) NOT NULL,
    system_amount DECIMAL(10,2) NOT NULL,
    superstar_percentage DECIMAL(5,2) DEFAULT 80.00,
    system_percentage DECIMAL(5,2) DEFAULT 20.00,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE CASCADE
);

-- Insert sample data for testing
INSERT INTO users (name, email, username, password, role, is_verified, is_blocked) VALUES
('John Doe', 'user@example.com', 'johndoe', '$2y$10$92IXUNpkjO0rOQ5byi.YT4PO.CX7la', 'user', TRUE, FALSE),
('Jane Smith', 'superstar@example.com', 'janesmith', '$2y$10$92IXUNpkjO0rOQ5byi.YT4PO.CX7la', 'superstar', TRUE, FALSE),
('Admin User', 'admin@example.com', 'admin', '$2y$10$92IXUNpkjO0rOQ5byi.YT4PO.CX7la', 'admin', TRUE, FALSE);

INSERT INTO superstars (user_id, display_name, bio, price_per_hour, is_available, rating, total_followers, status) VALUES
(2, 'Jane Smith', 'Professional superstar and entertainer', 50.00, TRUE, 4.5, 1500, 'active'),
(3, 'John Doe', 'Talented musician and performer', 75.00, TRUE, 4.8, 2500, 'active');

-- Insert sample posts
INSERT INTO superstar_posts (user_id, media_type, resource_type, resource_url_path, description, is_pg, is_disturbing) VALUES
(2, 'image', 'upload', 'posts/photo1.jpg', 'Having a great day at the studio!', FALSE, FALSE),
(3, 'video', 'url', 'https://example.com/video1.mp4', 'New music video coming soon!', FALSE, FALSE);

-- Insert sample subscriptions
INSERT INTO subscriptions (user_id, superstar_id) VALUES
(1, 2),
(1, 3);

-- Create indexes for better performance
CREATE INDEX idx_users_email ON users(email);
CREATE INDEX idx_users_role ON users(role);
CREATE INDEX idx_superstars_user_id ON superstars(user_id);
CREATE INDEX idx_superstars_status ON superstars(status);
CREATE INDEX idx_superstar_posts_user_id ON superstar_posts(user_id);
CREATE INDEX idx_subscriptions_user_id ON subscriptions(user_id);
CREATE INDEX idx_subscriptions_superstar_id ON subscriptions(superstar_id);
CREATE INDEX idx_conversations_user_id ON conversations(user_google_id);
CREATE INDEX idx_conversations_superstar_id ON conversations(superstar_id);
CREATE INDEX idx_messages_conversation_id ON messages(conversation_id);
CREATE INDEX idx_payments_user_id ON payments(user_google_id);
CREATE INDEX idx_payments_superstar_id ON payments(superstar_id);
CREATE INDEX idx_payments_status ON payments(payment_status);
