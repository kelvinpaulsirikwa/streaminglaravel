<?php
/**
 * User Superstar Controller - Pure PHP Version
 */

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../core/Request.php';
require_once __DIR__ . '/../../core/Response.php';
require_once __DIR__ . '/../../core/JWT.php';
require_once __DIR__ . '/../../models/Superstar.php';

class UserSuperStarController {
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

        $perPage = (int)($this->request->get('per_page', 15));

        // Get all superstars
        $sql = "
            SELECT id, user_id, display_name, bio, price_per_hour, is_available, rating, total_followers, status, created_at, updated_at
            FROM superstars
            ORDER BY created_at DESC
            LIMIT ?
        ";

        $superstars = $this->db->fetchAll($sql, [$perPage]);

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM superstars";
        $totalResult = $this->db->fetch($countSql);
        $total = $totalResult['total'];

        $pagination = [
            'current_page' => 1,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'total' => $total,
            'from' => 1,
            'to' => count($superstars),
            'has_more_pages' => $total > $perPage
        ];

        Response::success([
            'data' => $superstars,
            'pagination' => $pagination
        ], 'Superstars retrieved successfully');
    }

    public function show($id) {
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

        // Get superstar
        $superstar = $this->superstarModel->find($id);
        if (!$superstar) {
            Response::notFound('Superstar not found');
            return;
        }

        // Check if user is subscribed
        $subscriptionSql = "SELECT id FROM subscriptions WHERE user_id = ? AND superstar_id = ?";
        $isSubscribed = $this->db->fetch($subscriptionSql, [$payload['user_id'], $id]);

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
            'created_at' => $superstar['created_at'],
            'updated_at' => $superstar['updated_at']
        ];

        Response::success([
            'superstar' => $superstarData,
            'is_subscribed' => $isSubscribed ? true : false
        ], 'Superstar details retrieved successfully');
    }

    public function posts($id) {
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

        // Get superstar
        $superstar = $this->superstarModel->find($id);
        if (!$superstar) {
            Response::notFound('Superstar not found');
            return;
        }

        $perPage = (int)($this->request->get('per_page', 15));

        // Get superstar's posts
        $sql = "
            SELECT id, user_id, media_type, resource_type, resource_url_path, description, is_pg, is_disturbing, created_at, updated_at
            FROM superstar_posts
            WHERE user_id = ?
            ORDER BY created_at DESC
            LIMIT ? OFFSET 0
        ";

        $posts = $this->db->fetchAll($sql, [$superstar['user_id'], $perPage]);

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM superstar_posts WHERE user_id = ?";
        $totalResult = $this->db->fetch($countSql, [$superstar['user_id']]);
        $total = $totalResult['total'];

        $pagination = [
            'current_page' => 1,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'total' => $total,
            'from' => 1,
            'to' => count($posts),
            'has_more_pages' => $total > $perPage
        ];

        Response::success([
            'data' => $posts,
            'pagination' => $pagination
        ], 'Superstar posts retrieved successfully');
    }
}
