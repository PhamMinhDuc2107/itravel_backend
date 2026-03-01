<?php

namespace App\Enums;

enum TourStatusEnum: int
{
    case DRAFT = 0;
    case ACTIVE = 1;
    case INACTIVE = 2;
    case SOLDOUT = 3;

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Bản nháp',
            self::ACTIVE => 'Hoạt động',
            self::INACTIVE => 'Không hoạt động',
            self::SOLDOUT => 'Hết chỗ',
        };
    }

    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    public function isActive(): bool
    {
        return $this === self::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === self::INACTIVE;
    }

    public function isSoldout(): bool
    {
        return $this === self::SOLDOUT;
    }
}
