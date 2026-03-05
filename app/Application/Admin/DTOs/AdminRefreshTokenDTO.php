<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

final class AdminRefreshTokenDTO
{
    public function __construct(
        public readonly int $adminId,
        public readonly string $refreshToken,
        public readonly array $data = [],
        public readonly array $context = [],
    ) {}
}
