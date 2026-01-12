<?php
/**
 * Superstar Post Controller - Pure PHP Version
 */

require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../core/Request.php';
require_once __DIR__ . '/../../core/Response.php';
require_once __DIR__ . '/../../core/JWT.php';
require_once __DIR__ . '/../../models/Superstar.php';

class SuperstarPostController {
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

        $page = (int)($this->request->get('page', 1));
        $perPage = (int)($this->request->get('per_page', 15));
        $mediaType = $this->request->get('media_type');
        $isPg = $this->request->get('is_pg');
        $isDisturbing = $this->request->get('is_disturbing');

        // Get superstar's user_id
        $superstarSql = "SELECT user_id FROM superstars WHERE id = ?";
        $superstarResult = $this->db->fetch($superstarSql, [$payload['user_id']]);
        
        if (!$superstarResult) {
            Response::notFound('Superstar not found');
            return;
        }

        $superstarUserId = $superstarResult['user_id'];

        // Build query with filters
        $whereConditions = ["user_id = ?"];
        $params = [$superstarUserId];

        if ($mediaType) {
            $whereConditions[] = "media_type = ?";
            $params[] = $mediaType;
        }

        if ($isPg !== null) {
            $whereConditions[] = "is_pg = ?";
            $params[] = $isPg;
        }

        if ($isDisturbing !== null) {
            $whereConditions[] = "is_disturbing = ?";
            $params[] = $isDisturbing;
        }

        $whereClause = implode(' AND ', $whereConditions);
        $offset = ($page - 1) * $perPage;

        $sql = "
            SELECT id, user_id, media_type, resource_type, resource_url_path, description, is_pg, is_disturbing, created_at, updated_at
            FROM superstar_posts 
            WHERE {$whereClause}
            ORDER BY created_at DESC
            LIMIT ? OFFSET ?
        ";

        $posts = $this->db->fetchAll($sql, array_merge($params, [$perPage, $offset]));

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM superstar_posts WHERE {$whereClause}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'];

        $pagination = [
            'current_page' => $page,
            'last_page' => ceil($total / $perPage),
            'per_page' => $perPage,
            'total' => $total,
            'from' => $offset + 1,
            'to' => $offset + count($posts),
            'has_more_pages' => $page < ceil($total / $perPage)
        ];

        Response::success([
            'posts' => $posts,
            'pagination' => $pagination
        ], 'Posts retrieved successfully');
    }

    public function store() {
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
            'media_type' => 'required|in:image,video',
            'resource_type' => 'required|in:upload,url',
            'resource_url_path' => 'required|string|max:500',
            'description' => 'nullable|string',
            'is_pg' => 'boolean',
            'is_disturbing' => 'boolean'
        ];

        $errors = $this->request->validate($rules);
        if (!empty($errors)) {
            Response::validationError($errors);
            return;
        }

        // Get superstar's user_id
        $superstarSql = "SELECT user_id FROM superstars WHERE id = ?";
        $superstarResult = $this->db->fetch($superstarSql, [$payload['user_id']]);
        
        if (!$superstarResult) {
            Response::notFound('Superstar not found');
            return;
        }

        $superstarUserId = $superstarResult['user_id'];

        // Create post
        $postSql = "
            INSERT INTO superstar_posts 
            (user_id, media_type, resource_type, resource_url_path, description, is_pg, is_disturbing, created_at, updated_at) 
            VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
        ";

        $this->db->query($postSql, [
            $superstarUserId,
            $this->request->get('media_type'),
            $this->request->get('resource_type'),
            $this->request->get('resource_url_path'),
            $this->request->get('description'),
            $this->request->get('is_pg', false),
            $this->request->get('is_disturbing', false)
        ]);

        $postId = $this->db->getConnection()->lastInsertId();

        // Get created post
        $post = $this->db->fetch("SELECT * FROM superstar_posts WHERE id = ?", [$postId]);

        Response::success([
            'message' => 'Post created successfully',
            'post' => $post
        ], 'Post created successfully', 201);
    }

    public function show($id) {
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

        // Get superstar's user_id
        $superstarSql = "SELECT user_id FROM superstars WHERE id = ?";
        $superstarResult = $this->db->fetch($superstarSql, [$payload['user_id']]);
        
        if (!$superstarResult) {
            Response::notFound('Superstar not found');
            return;
        }

        $superstarUserId = $superstarResult['user_id'];

        // Get post
        $sql = "
            SELECT * FROM superstar_posts 
            WHERE user_id = ? AND id = ?
        ";

        $post = $this->db->fetch($sql, [$superstarUserId, $id]);

        if (!$post) {
            Response::notFound('Post not found');
            return;
        }

        Response::success([
            'post' => $post
        ], 'Post retrieved successfully');
    }

    public function update($id) {
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

        // Get superstar's user_id
        $superstarSql = "SELECT user_id FROM superstars WHERE id = ?";
        $superstarResult = $this->db->fetch($superstarSql, [$payload['user_id']]);
        
        if (!$superstarResult) {
            Response::notFound('Superstar not found');
            return;
        }

        $superstarUserId = $superstarResult['user_id'];

        // Check if post exists and belongs to superstar
        $existingPost = $this->db->fetch("SELECT id FROM superstar_posts WHERE user_id = ? AND id = ?", [$superstarUserId, $id]);
        if (!$existingPost) {
            Response::notFound('Post not found');
            return;
        }

        // Build update data
        $updateFields = [];
        $params = [$id, $superstarUserId];

        if ($this->request->has('media_type')) {
            $updateFields[] = "media_type = ?";
            $params[] = $this->request->get('media_type');
        }

        if ($this->request->has('resource_type')) {
            $updateFields[] = "resource_type = ?";
            $params[] = $this->request->get('resource_type');
        }

        if ($this->request->has('resource_url_path')) {
            $updateFields[] = "resource_url_path = ?";
            $params[] = $this->request->get('resource_url_path');
        }

        if ($this->request->has('description')) {
            $updateFields[] = "description = ?";
            $params[] = $this->request->get('description');
        }

        if ($this->request->has('is_pg')) {
            $updateFields[] = "is_pg = ?";
            $params[] = $this->request->get('is_pg');
        }

        if ($this->request->has('is_disturbing')) {
            $updateFields[] = "is_disturbing = ?";
            $params[] = $this->request->get('is_disturbing');
        }

        if (empty($updateFields)) {
            Response::error('No valid fields to update', 400);
            return;
        }

        $updateFields[] = "updated_at = NOW()";
        $setClause = implode(', ', $updateFields);

        $sql = "UPDATE superstar_posts SET {$setClause} WHERE id = ? AND user_id = ?";
        $this->db->query($sql, $params);

        // Get updated post
        $post = $this->db->fetch("SELECT * FROM superstar_posts WHERE id = ?", [$id]);

        Response::success([
            'message' => 'Post updated successfully',
            'post' => $post
        ], 'Post updated successfully');
    }

    public function destroy($id) {
        if ($this->request->method() !== 'DELETE') {
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

        // Get superstar's user_id
        $superstarSql = "SELECT user_id FROM superstars WHERE id = ?";
        $superstarResult = $this->db->fetch($superstarSql, [$payload['user_id']]);
        
        if (!$superstarResult) {
            Response::notFound('Superstar not found');
            return;
        }

        $superstarUserId = $superstarResult['user_id'];

        // Check if post exists and belongs to superstar
        $existingPost = $this->db->fetch("SELECT id FROM superstar_posts WHERE user_id = ? AND id = ?", [$superstarUserId, $id]);
        if (!$existingPost) {
            Response::notFound('Post not found');
            return;
        }

        // Delete post
        $sql = "DELETE FROM superstar_posts WHERE id = ? AND user_id = ?";
        $this->db->query($sql, [$id, $superstarUserId]);

        Response::success(null, 'Post deleted successfully');
    }
}
