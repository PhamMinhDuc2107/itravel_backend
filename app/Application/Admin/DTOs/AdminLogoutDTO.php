<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

final class AdminLogoutDTO
{
    public function __construct(
        public readonly int $adminId,
        public readonly ?string $refreshToken = null,
    ) {}
}
