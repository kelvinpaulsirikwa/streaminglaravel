<?php
/**
 * Response Class - Pure PHP Version
 */

class Response {
    public static function json($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public static function success($data = null, $message = 'Success') {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], 200);
    }

    public static function error($message, $statusCode = 400, $data = null) {
        self::json([
            'success' => false,
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }

    public static function unauthorized($message = 'Unauthorized') {
        self::error($message, 401);
    }

    public static function notFound($message = 'Not Found') {
        self::error($message, 404);
    }

    public static function validationError($errors) {
        self::json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $errors
        ], 422);
    }

    public static function serverError($message = 'Internal Server Error') {
        self::error($message, 500);
    }
}
