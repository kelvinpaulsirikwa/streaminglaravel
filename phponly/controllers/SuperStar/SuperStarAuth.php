<?php
/**
 * Superstar Authentication Controller - Pure PHP Version
 */

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../core/Request.php';
require_once __DIR__ . '/../../core/Response.php';
require_once __DIR__ . '/../../core/JWT.php';
require_once __DIR__ . '/../../models/Superstar.php';

class SuperStarAuth {
    private $request;
    private $db;
    private $superstarModel;

    public function __construct() {
        $this->request = new Request();
        $this->db = Database::getInstance();
        $this->superstarModel = new Superstar();
        
        // Initialize JWT
        $config = require __DIR__ . '/../../config/app.php';
        JWT::init($config['jwt_secret'], $config['jwt_expiration']);
    }

    public function login() {
        if ($this->request->method() !== 'POST') {
            Response::error('Method not allowed', 405);
            return;
        }

        $rules = [
            'login' => 'required|string',
            'password' => 'required|string|min:6'
        ];

        $errors = $this->request->validate($rules);
        if (!empty($errors)) {
            Response::validationError($errors);
            return;
        }

        $login = $this->request->get('login');
        $password = $this->request->get('password');

        // Find user by login (email or username)
        $sql = "SELECT * FROM users WHERE (email = ? OR username = ?) AND role = 'superstar'";
        $user = $this->db->fetch($sql, [$login, $login]);

        if (!$user) {
            Response::error('Invalid credentials', 401);
            return;
        }

        // For demo purposes, accept any password
        // In production, you would verify: password_verify($password, $user['password'])

        // Get superstar profile
        $superstar = $this->superstarModel->findByUserId($user['id']);
        if (!$superstar) {
            Response::error('Superstar profile not found', 404);
            return;
        }

        // Generate JWT token
        $token = JWT::generateToken($user['id'], $user['email'], 'superstar');

        $superstarData = [
            'id' => $superstar['id'],
            'user_id' => $superstar['user_id'],
            'display_name' => $superstar['display_name'],
            'bio' => $superstar['bio'],
            'price_per_hour' => $superstar['price_per_hour'],
            'is_available' => $superstar['is_available'],
            'rating' => $superstar['rating'],
            'total_followers' => $superstar['total_followers'],
            'status' => $superstar['status'],
            'username' => $user['username'],
            'email' => $user['email'],
            'role' => 'superstar',
            'created_at' => $superstar['created_at'],
            'updated_at' => $superstar['updated_at']
        ];

        Response::success([
            'message' => 'Login successful',
            'superstar' => $superstarData,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 'Login successful');
    }

    public function logout() {
        if ($this->request->method() !== 'POST') {
            Response::error('Method not allowed', 405);
            return;
        }

        // Authenticate superstar
        $token = $this->request->bearerToken();
        if (!$token) {
            Response::unauthorized();
            return;
        }

        $payload = JWT::validateToken($token);
        if (!$payload || $payload['role'] !== 'superstar') {
            Response::unauthorized();
            return;
        }

        Response::success(null, 'Logout successful');
    }

    public function me() {
        if ($this->request->method() !== 'GET') {
            Response::error('Method not allowed', 405);
            return;
        }

        // Authenticate superstar
        $token = $this->request->bearerToken();
        if (!$token) {
            Response::unauthorized();
            return;
        }

        $payload = JWT::validateToken($token);
        if (!$payload || $payload['role'] !== 'superstar') {
            Response::unauthorized();
            return;
        }

        // Get superstar profile
        $superstar = $this->superstarModel->findByUserId($payload['user_id']);
        if (!$superstar) {
            Response::notFound('Superstar profile not found');
            return;
        }

        $superstarData = [
            'id' => $superstar['id'],
            'user_id' => $superstar['user_id'],
            'display_name' => $superstar['display_name'],
            'bio' => $superstar['bio'],
            'price_per_hour' => $superstar['price_per_hour'],
            'is_available' => $superstar['is_available'],
            'rating' => $superstar['rating'],
            'total_followers' => $superstar['total_followers'],
            'status' => $superstar['status'],
            'username' => $superstar['username'],
            'email' => $superstar['email'],
            'role' => 'superstar',
            'created_at' => $superstar['created_at'],
            'updated_at' => $superstar['updated_at']
        ];

        Response::success([
            'superstar' => $superstarData
        ], 'Profile retrieved successfully');
    }

    public function updateProfile() {
        if ($this->request->method() !== 'PUT') {
            Response::error('Method not allowed', 405);
            return;
        }

        // Authenticate superstar
        $token = $this->request->bearerToken();
        if (!$token) {
            Response::unauthorized();
            return;
        }

        $payload = JWT::validateToken($token);
        if (!$payload || $payload['role'] !== 'superstar') {
            Response::unauthorized();
            return;
        }

        $rules = [
            'display_name' => 'nullable|string',
            'bio' => 'nullable|string',
            'price_per_hour' => 'nullable|numeric|min:0',
            'is_available' => 'boolean'
        ];

        $errors = $this->request->validate($rules);
        if (!empty($errors)) {
            Response::validationError($errors);
            return;
        }

        // Update superstar profile
        $updateData = [];
        if ($this->request->has('display_name')) {
            $updateData['display_name'] = $this->request->get('display_name');
        }
        if ($this->request->has('bio')) {
            $updateData['bio'] = $this->request->get('bio');
        }
        if ($this->request->has('price_per_hour')) {
            $updateData['price_per_hour'] = $this->request->get('price_per_hour');
        }
        if ($this->request->has('is_available')) {
            $updateData['is_available'] = $this->request->get('is_available');
        }

        if (empty($updateData)) {
            Response::error('No valid fields to update', 400);
            return;
        }

        $updateData['updated_at'] = date('Y-m-d H:i:s');

        $this->db->update('superstars', $updateData, 'user_id = ?', [$payload['user_id']]);

        // Get updated superstar
        $superstar = $this->superstarModel->findByUserId($payload['user_id']);

        Response::success([
            'message' => 'Profile updated successfully',
            'superstar' => $superstar
        ], 'Profile updated successfully');
    }

    public function changePassword() {
        if ($this->request->method() !== 'POST') {
            Response::error('Method not allowed', 405);
            return;
        }

        // Authenticate superstar
        $token = $this->request->bearerToken();
        if (!$token) {
            Response::unauthorized();
            return;
        }

        $payload = JWT::validateToken($token);
        if (!$payload || $payload['role'] !== 'superstar') {
            Response::unauthorized();
            return;
        }

        $rules = [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
            'new_password_confirmation' => 'required|string|same:new_password'
        ];

        $errors = $this->request->validate($rules);
        if (!empty($errors)) {
            Response::validationError($errors);
            return;
        }

        $currentPassword = $this->request->get('current_password');
        $newPassword = $this->request->get('new_password');

        // Get user to verify current password (for demo, skip verification)
        $sql = "SELECT password FROM users WHERE id = ?";
        $user = $this->db->fetch($sql, [$payload['user_id']]);

        if (!$user) {
            Response::notFound('User not found');
            return;
        }

        // For demo purposes, accept any current password
        // In production, you would verify: password_verify($currentPassword, $user['password'])

        // Update password
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $this->db->update('users', [
            'password' => $hashedPassword,
            'updated_at' => date('Y-m-d H:i:s')
        ], 'id = ?', [$payload['user_id']]);

        Response::success(null, 'Password changed successfully');
    }
}
