<?php

namespace App\Services;

use App\Exceptions\UnauthorizedException;
use App\Models\AdminRefreshToken;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class JwtService
{
    private string $secretKey;
    private string $algorithm;
    private int $accessTokenTtl;
    private int $refreshTokenTtl;

    public function __construct()
    {
        $this->secretKey = config('app.key');
        $this->algorithm = 'HS256';
        $this->accessTokenTtl = config('jwt.access_token_ttl', 3600); // 1 hour
        $this->refreshTokenTtl = config('jwt.refresh_token_ttl', 604800); // 7 days
    }

    public function generateAccessToken(int $userId, array $claims = []): string
    {
        $payload = array_merge([
            'iss' => config('app.url'),
            'sub' => $userId,
            'iat' => time(),
            'exp' => time() + $this->accessTokenTtl,
            'type' => 'access',
        ], $claims);

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function generateRefreshToken(int $userId, string $userAgent = null, string $ipAddress = null): array
    {
        $token = Str::random(64);
        $hashedToken = Hash::make($token);

        $refreshToken = AdminRefreshToken::create([
            'user_id' => $userId,
            'token' => $hashedToken,
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
            'expires_at' => Carbon::now()->addSeconds($this->refreshTokenTtl),
        ]);

        return [
            'token' => $token,
            'expires_at' => $refreshToken->expires_at,
        ];
    }

    public function validateAccessToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return (array) $decoded;
        } catch (\Exception $e) {
            throw new UnauthorizedException('Token không hợp lệ hoặc đã hết hạn');
        }
    }

    public function validateRefreshToken(string $token, int $userId): AdminRefreshToken
    {
        $refreshTokens = AdminRefreshToken::where('user_id', $userId)
            ->where('is_revoked', 0)
            ->where('expires_at', '>', now())
            ->get();

        foreach ($refreshTokens as $refreshToken) {
            if (Hash::check($token, $refreshToken->token)) {
                return $refreshToken;
            }
        }

        throw new UnauthorizedException('Refresh token không hợp lệ hoặc đã hết hạn');
    }

    public function refreshAccessToken(string $refreshToken, int $userId): array
    {
        $validRefreshToken = $this->validateRefreshToken($refreshToken, $userId);

        $newAccessToken = $this->generateAccessToken($userId);

        return [
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer',
            'expires_in' => $this->accessTokenTtl,
        ];
    }

    public function revokeRefreshToken(string $token, int $userId): bool
    {
        $refreshToken = $this->validateRefreshToken($token, $userId);
        $refreshToken->update(['is_revoked' => 1]);

        return true;
    }

    public function revokeAllRefreshTokens(int $userId): int
    {
        return AdminRefreshToken::where('user_id', $userId)
            ->where('is_revoked', 0)
            ->update(['is_revoked' => 1]);
    }

    public function getUserIdFromToken(string $token): int
    {
        $payload = $this->validateAccessToken($token);
        return $payload['sub'] ?? throw new UnauthorizedException('Token không chứa user ID');
    }

    public function cleanupExpiredTokens(): int
    {
        return AdminRefreshToken::where('expires_at', '<', now())->delete();
    }
}
