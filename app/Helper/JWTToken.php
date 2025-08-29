<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTToken
{
    public static function generateToken($UserEmail, $UserId): string
    {

        $key     = env('JWT_SECRET');
        $payload = [
            'iss'   => 'laravel-token',
            'iat'   => time(),
            'exp'   => time() + 60 * 60, // 1 hour expiration
            'email' => $UserEmail,
            'id'    => $UserId,

        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    public static function ReadToken($token): object | string
    {
        try {
            if ($token === null) {
                throw new \Exception('Token not provided');
            } else {
                $key = env('JWT_SECRET');
                return JWT::decode($token, new Key($key, 'HS256'));

            }
        } catch (Exception $e) {
            return "unauthorized";
        }
    }
}
