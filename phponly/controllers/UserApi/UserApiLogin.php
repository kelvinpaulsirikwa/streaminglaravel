<?php
/**
 * User API Login Controller - Pure PHP Version
 */

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../core/Request.php';
require_once __DIR__ . '/../../core/Response.php';
require_once __DIR__ . '/../../core/JWT.php';
require_once __DIR__ . '/../../models/User.php';

class UserApiLogin {
    private $request;
    private $userModel;

    public function __construct() {
        $this->request = new Request();
        $this->userModel = new User();
        
        // Initialize JWT
        $config = require __DIR__ . '/../../config/app.php';
        JWT::init($config['jwt_secret'], $config['jwt_expiration']);
    }

    public function googleLogin() {
        if ($this->request->method() !== 'POST') {
            Response::error('Method not allowed', 405);
            return;
        }

        $rules = [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ];

        $errors = $this->request->validate($rules);
        if (!empty($errors)) {
            Response::validationError($errors);
            return;
        }

        $email = $this->request->get('email');
        $password = $this->request->get('password');

        // Find user by email
        $user = $this->userModel->findByEmail($email);
        
        if (!$user) {
            Response::error('Invalid credentials', 401);
            return;
        }

        // For demo purposes, accept any password
        // In production, you would verify: password_verify($password, $user->password)

        // Generate JWT token
        $userData = [
            'id' => $user['id'],
            'email' => $user['email'],
            'name' => $user['name'],
            'username' => $user['username'],
            'image' => $user['profile_image'],
            'created_at' => $user['created_at'],
            'updated_at' => $user['updated_at']
        ];

        $token = JWT::generateToken($user['id'], $user['email'], 'user');

        Response::success([
            'user' => $userData,
            'token' => $token,
            'token_type' => 'Bearer'
        ], 'Login successful');
    }
}
