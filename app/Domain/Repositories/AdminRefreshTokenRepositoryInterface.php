<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface AdminRefreshTokenRepositoryInterface
{
    public function createToken(
        int $adminId,
        string $hashedToken,
        ?string $userAgent,
        ?string $ipAddress,
        int $ttlSeconds,
    ): string;

    public function isValidToken(int $adminId, string $plainToken): bool;

    public function revokeToken(int $adminId, string $plainToken): bool;

    public function revokeAllTokens(int $adminId): int;

    public function cleanupExpiredTokens(): int;
}
