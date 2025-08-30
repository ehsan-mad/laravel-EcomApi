<?php
namespace App\Helper;

use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Log;

class JWTToken
{
    /**
     * Cached resolved signing key (process lifetime)
     */
    private static ?string $cachedKey = null; // in-process fallback

    /**
     * Resolve the HMAC signing key with graceful fallbacks:
     * 1. JWT_SECRET env
     * 2. APP_KEY (decoded if base64:)
     * 3. Ephemeral random key (will invalidate tokens on restart)
     */
    private static function resolveKey(): string
    {
        if (self::$cachedKey !== null) {
            return self::$cachedKey;
        }

        $key = env('JWT_SECRET');

        // Fallback to app key (strip base64: prefix) if JWT secret missing/empty
        if (empty($key)) {
            $appKey = config('app.key');
            if (! empty($appKey)) {
                if (str_starts_with($appKey, 'base64:')) {
                    $decoded = base64_decode(substr($appKey, 7));
                    $key = $decoded !== false ? $decoded : $appKey;
                } else {
                    $key = $appKey;
                }
            }
        }

        // Generate ephemeral key if still empty (ensures encode won't fail)
        if (empty($key)) {
            $key = bin2hex(random_bytes(32));
            Log::warning('JWT_SECRET missing; generated ephemeral key. Tokens will invalidate on restart.');
        }

        self::$cachedKey = $key;
        return $key;
    }

    /**
     * Generate a short-lived (1h) JWT token for given email & user id.
     */
    public static function generateToken($UserEmail, $UserId): string
    {
        $key = self::resolveKey();
        $payload = [
            'iss'   => 'laravel-token',
            'iat'   => time(),
            'exp'   => time() + 60 * 60, // 1 hour expiration
            'email' => $UserEmail,
            'id'    => $UserId,
        ];
        return JWT::encode($payload, $key, 'HS256');
    }

    /**
     * Decode a JWT token; returns decoded payload object or "unauthorized" string.
     */
    public static function ReadToken($token): object | string
    {
        try {
            if ($token === null) {
                throw new \Exception('Token not provided');
            }
            $key = self::resolveKey();
            return JWT::decode($token, new Key($key, 'HS256'));
        } catch (Exception $e) {
            return "unauthorized";
        }
    }
}
