<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\External;

use App\Domain\Exceptions\UnauthorizedException;
use App\Infrastructure\Services\Contracts\JwtServiceInterface;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class JwtService implements JwtServiceInterface
{
    private int $accessTokenTtl;

    public function __construct()
    {
        $this->accessTokenTtl = (int) config('jwt.access_token_ttl', 3600);
    }

    public function generateAccessToken(array $data, array $claims = [], array $context = []): string
    {
        $adminId = (int) ($data['admin_id'] ?? $data['id'] ?? 0);
        if ($adminId <= 0) {
            throw new UnauthorizedException('Token data khong chua admin id hop le');
        }

        $jwtClaims = array_merge([
            'data' => $data,
            'context' => $context,
            'type' => 'access',
        ], $claims);

        $ttlMinutes = max(1, (int) ceil($this->accessTokenTtl / 60));

        JWTAuth::factory()->setTTL($ttlMinutes);

        return JWTAuth::claims($jwtClaims)->fromSubject($this->buildSubject($adminId));
    }

    private function buildSubject(int $adminId): JWTSubject
    {
        return new readonly class($adminId) implements JWTSubject {
            public function __construct(private int $adminId) {}

            public function getJWTIdentifier(): int
            {
                return $this->adminId;
            }

            /**
             * @return array<string, scalar|array|null>
             */
            public function getJWTCustomClaims(): array
            {
                return [];
            }
        };
    }

    public function validateAccessToken(string $token): array
    {
        try {
            return JWTAuth::setToken($token)->getPayload()->toArray();
        } catch (\Throwable $e) {
            throw new UnauthorizedException('Token khong hop le hoac da het han');
        }
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

    public function getAccessTokenTtl(): int
    {
        return $this->accessTokenTtl;
    }
}
