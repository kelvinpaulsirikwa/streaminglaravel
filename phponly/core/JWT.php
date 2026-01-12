<?php
/**
 * JWT Class - Pure PHP Version
 */

class JWT {
    private static $secret;
    private static $expiration;

    public static function init($secret, $expiration = 3600) {
        self::$secret = $secret;
        self::$expiration = $expiration;
    }

    public static function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode($payload);
        
        $headerEncoded = self::base64UrlEncode($header);
        $payloadEncoded = self::base64UrlEncode($payload);
        
        $signature = hash_hmac('sha256', $headerEncoded . '.' . $payloadEncoded, self::$secret, true);
        $signatureEncoded = self::base64UrlEncode($signature);
        
        return $headerEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    public static function decode($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            return false;
        }
        
        $header = json_decode(self::base64UrlDecode($parts[0]), true);
        $payload = json_decode(self::base64UrlDecode($parts[1]), true);
        $signature = self::base64UrlDecode($parts[2]);
        
        $expectedSignature = hash_hmac('sha256', $parts[0] . '.' . $parts[1], self::$secret, true);
        
        if (!hash_equals($signature, $expectedSignature)) {
            return false;
        }
        
        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }
        
        return $payload;
    }

    private static function base64UrlEncode($data) {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }

    private static function base64UrlDecode($data) {
        $base64 = str_replace(['-', '_'], ['+', '/', '/'], $data);
        return base64_decode($base64);
    }

    public static function generateToken($userId, $email, $role = 'user') {
        $payload = [
            'user_id' => $userId,
            'email' => $email,
            'role' => $role,
            'iat' => time(),
            'exp' => time() + self::$expiration
        ];
        
        return self::encode($payload);
    }

    public static function validateToken($token) {
        $payload = self::decode($token);
        
        if (!$payload) {
            return false;
        }
        
        return $payload;
    }
}
