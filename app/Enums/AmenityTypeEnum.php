<?php

namespace App\Enums;

enum AmenityTypeEnum: int
{
    case HOTEL = 0;
    case ROOM = 1;
    case BOTH = 2;

    public function label(): string
    {
        return match($this) {
            self::HOTEL => 'Khách sạn',
            self::ROOM => 'Phòng',
            self::BOTH => 'Cả hai',
        };
    }

    public function isHotel(): bool
    {
        return $this === self::HOTEL;
    }

    public function isRoom(): bool
    {
        return $this === self::ROOM;
    }

    public function isBoth(): bool
    {
        return $this === self::BOTH;
    }
}
