<?php

namespace App\Enums;

enum TourScheduleStatusEnum: int
{
    case OPEN = 0;
    case FULL = 1;
    case CANCELLED = 2;
    case DEPARTED = 3;

    public function label(): string
    {
        return match($this) {
            self::OPEN => 'Mở',
            self::FULL => 'Đầy',
            self::CANCELLED => 'Hủy',
            self::DEPARTED => 'Đã khởi hành',
        };
    }

    public function isOpen(): bool
    {
        return $this === self::OPEN;
    }

    public function isFull(): bool
    {
        return $this === self::FULL;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    public function isDeparted(): bool
    {
        return $this === self::DEPARTED;
    }
}
