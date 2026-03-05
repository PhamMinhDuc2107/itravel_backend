<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use App\Domain\Enums\StatusStateEnum;

final class AdminEntity
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly ?string $phone,
        public readonly ?string $avatar,
        public readonly StatusStateEnum $status,
        public readonly ?string $last_login_at,
        public readonly ?string $created_at,
    ) {}

    public function isActive(): bool
    {
        return $this->status === StatusStateEnum::ACTIVE;
    }
}
