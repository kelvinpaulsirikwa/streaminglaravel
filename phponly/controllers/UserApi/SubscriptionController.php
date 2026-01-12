<?php
/**
 * User API Subscription Controller - Pure PHP Version
 */

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../core/Request.php';
require_once __DIR__ . '/../../core/Response.php';
require_once __DIR__ . '/../../core/JWT.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Superstar.php';

class SubscriptionController {
    private $request;
    private $db;
    private $userModel;
    private $superstarModel;

    public function __construct() {
        $this->request = new Request();
        $this->db = Database::getInstance();
        $this->userModel = new User();
        $this->superstarModel = new Superstar();
        
        // Initialize JWT
        $config = require __DIR__ . '/../../config/app.php';
        JWT::init($config['jwt_secret'], $config['jwt_expiration']);
    }

    public function index() {
        if ($this->request->method() !== 'GET') {
            Response::error('Method not allowed', 405);
            return;
        }

        // Authenticate user
        $token = $this->request->bearerToken();
        if (!$token) {
            Response::unauthorized();
            return;
        }

        $payload = JWT::validateToken($token);
        if (!$payload) {
            Response::unauthorized();
            return;
        }

        $page = (int)($this->request->get('page', 1));
        $perPage = (int)($this->request->get('per_page', 15));

        // Get user's subscriptions with superstar details
        $sql = "
            SELECT s.*, u.name as user_name, u.username 
            FROM subscriptions sub
            JOIN superstars s ON sub.superstar_id = s.id
            JOIN users u ON s.user_id = u.id
            WHERE sub.user_id = ?
            ORDER BY sub.created_at DESC
            LIMIT ? OFFSET ?
        ";
        
        $offset = ($page - 1) * $perPage;
        $subscriptions = $this->db->fetchAll($sql, [$payload['user_id'], $perPage, $offset]);

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM subscriptions WHERE user_id = ?";
        $totalResult = $this->db->fetch($countSql, [$payload['user_id']]);
        $total = $totalResult['total'];

        $pagination = [
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'total' => $total,
            'from' => $offset + 1,
            'to' => $offset + count($subscriptions),
            'has_more_pages' => $page < ceil($total / $perPage)
        ];

        Response::success([
            'data' => $subscriptions,
            'pagination' => $pagination
        ], 'Subscriptions retrieved successfully');
    }

    public function store() {
        if ($this->request->method() !== 'POST') {
            Response::error('Method not allowed', 405);
            return;
        }

        // Authenticate user
        $token = $this->request->bearerToken();
        if (!$token) {
            Response::unauthorized();
            return;
        }

        $payload = JWT::validateToken($token);
        if (!$payload) {
            Response::unauthorized();
            return;
        }

        $rules = [
            'superstar_id' => 'required|integer'
        ];

        $errors = $this->request->validate($rules);
        if (!empty($errors)) {
            Response::validationError($errors);
            return;
        }

        $superstarId = $this->request->get('superstar_id');
        $userId = $payload['user_id'];

        // Check if superstar exists
        $superstar = $this->superstarModel->find($superstarId);
        if (!$superstar) {
            Response::notFound('Superstar not found');
            return;
        }

        // Check if already subscribed
        $existingSql = "SELECT id FROM subscriptions WHERE user_id = ? AND superstar_id = ?";
        $existing = $this->db->fetch($existingSql, [$userId, $superstarId]);
        
        if ($existing) {
            Response::error('Already subscribed to this superstar', 400);
            return;
        }

        // Create subscription
        $subscriptionSql = "
            INSERT INTO subscriptions (user_id, superstar_id, created_at, updated_at) 
            VALUES (?, ?, NOW(), NOW())
        ";
        
        $this->db->query($subscriptionSql, [$userId, $superstarId]);

        // Update superstar followers count
        $updateSql = "UPDATE superstars SET total_followers = total_followers + 1 WHERE id = ?";
        $this->db->query($updateSql, [$superstarId]);

        Response::success(null, 'Subscribed successfully');
    }
}
