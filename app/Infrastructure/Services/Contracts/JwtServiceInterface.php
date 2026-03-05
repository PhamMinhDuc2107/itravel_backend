<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\Contracts;

interface JwtServiceInterface
{
    /**
     * @param array<string, scalar|array|null> $data
     * @param array<string, scalar|array|null> $claims
     * @param array<string, scalar|array|null> $context
     */
    public function generateAccessToken(array $data, array $claims = [], array $context = []): string;

    /**
     * @return array<string, scalar|array|null>
     */
    public function validateAccessToken(string $token): array;

    /**
     * @param array<string, scalar|array|null> $payload
     */
    public function getAdminIdFromPayload(array $payload): int;

    public function getAdminIdFromToken(string $token): int;

    public function getAccessTokenTtl(): int;
}
