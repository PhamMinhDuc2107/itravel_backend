<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

final class AdminLoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $userAgent,
        public readonly ?string $ipAddress,
        public readonly array $context = [],
    ) {}
}
