<?php

namespace App\Domain\Enums;

enum StatusStateEnum: int
{
    case INACTIVE = 0;
    case ACTIVE = 1;

    public function label(): string
    {
        return match($this) {
            self::INACTIVE => 'Không hoạt động',
            self::ACTIVE => 'Hoạt động',
        };
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }
}
