<?php

declare(strict_types=1);

namespace App\Auth;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public function __construct(
        private string $secret,
        private string $algo = 'HS256',
        private int $ttl = 3600 // seconds
    ) {}

    public function generateToken(array $claims): string
    {
        $now = time();
        $payload = array_merge($claims, [
            'iat' => $now,
            'exp' => $now + $this->ttl,
        ]);

        return JWT::encode($payload, $this->secret, $this->algo);
    }

    public function validateToken(string $token): array
    {
        $decoded = JWT::decode($token, new Key($this->secret, $this->algo));
        return (array) $decoded;
    }
}
