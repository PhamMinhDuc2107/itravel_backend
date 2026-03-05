<?php

namespace App\Infrastructure\Services\External;

use App\Domain\Exceptions\UnauthorizedException;
use App\Infrastructure\Database\Models\AdminRefreshTokenModel;
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
        $this->accessTokenTtl = (int) config('jwt.access_token_ttl', 3600);
        $this->refreshTokenTtl = (int) config('jwt.refresh_token_ttl', 604800);
    }

    public function generateAccessToken(array $data, array $claims = [], array $context = []): string
    {
        $subject = $data['admin_id'] ?? $data['id'] ?? null;

        $payload = array_merge([
            'iss' => config('app.url'),
            'sub' => $subject,
            'data' => $data,
            'context' => $context,
            'iat' => time(),
            'exp' => time() + $this->accessTokenTtl,
            'type' => 'access',
        ], $claims);

        return JWT::encode($payload, $this->secretKey, $this->algorithm);
    }

    public function generateRefreshToken(int $adminId, ?string $userAgent = null, ?string $ipAddress = null): array
    {
        $rawToken = Str::random(64);
        $hashedToken = Hash::make($rawToken);

        $refreshToken = AdminRefreshTokenModel::create([
            'admin_id' => $adminId,
            'token' => $hashedToken,
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
            'expires_at' => Carbon::now()->addSeconds($this->refreshTokenTtl),
        ]);

        return [
            'token' => $rawToken,
            'expires_at' => $refreshToken->expires_at,
        ];
    }

    public function validateAccessToken(string $token): array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));

            return (array) $decoded;
        } catch (\Throwable $e) {
            throw new UnauthorizedException('Token khong hop le hoac da het han');
        }
    }

    public function validateRefreshToken(string $token, int $adminId): AdminRefreshTokenModel
    {
        $refreshTokens = AdminRefreshTokenModel::query()
            ->where('admin_id', $adminId)
            ->where('is_revoked', 0)
            ->where('expires_at', '>', now())
            ->get();

        foreach ($refreshTokens as $refreshToken) {
            if (Hash::check($token, $refreshToken->token)) {
                return $refreshToken;
            }
        }

        throw new UnauthorizedException('Refresh token khong hop le hoac da het han');
    }

    public function refreshAccessToken(string $refreshToken, int $adminId, array $data = [], array $context = []): array
    {
        $this->validateRefreshToken($refreshToken, $adminId);

        if ($data === []) {
            $data = ['admin_id' => $adminId];
        }

        $newAccessToken = $this->generateAccessToken($data, [], $context);

        return [
            'access_token' => $newAccessToken,
            'token_type' => 'Bearer',
            'expires_in' => $this->accessTokenTtl,
        ];
    }

    public function revokeRefreshToken(string $token, int $adminId): bool
    {
        $refreshToken = $this->validateRefreshToken($token, $adminId);
        $refreshToken->update(['is_revoked' => 1]);

        return true;
    }

    public function revokeAllRefreshTokens(int $adminId): int
    {
        return AdminRefreshTokenModel::query()
            ->where('admin_id', $adminId)
            ->where('is_revoked', 0)
            ->update(['is_revoked' => 1]);
    }

    public function getAdminIdFromToken(string $token): int
    {
        $payload = $this->validateAccessToken($token);

        if (isset($payload['sub']) && is_numeric($payload['sub'])) {
            return (int) $payload['sub'];
        }

        $data = (array) ($payload['data'] ?? []);
        if (isset($data['admin_id']) && is_numeric($data['admin_id'])) {
            return (int) $data['admin_id'];
        }

        if (isset($data['id']) && is_numeric($data['id'])) {
            return (int) $data['id'];
        }

        $context = (array) ($payload['context'] ?? []);
        if (isset($context['admin_id']) && is_numeric($context['admin_id'])) {
            return (int) $context['admin_id'];
        }

        throw new UnauthorizedException('Token khong chua admin id');
    }

    public function cleanupExpiredTokens(): int
    {
        return AdminRefreshTokenModel::query()
            ->where('expires_at', '<', now())
            ->delete();
    }
}
