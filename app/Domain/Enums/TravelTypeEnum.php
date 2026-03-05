<?php

namespace App\Domain\Enums;

enum TravelTypeEnum: int
{
    case SOLO = 0;
    case COUPLE = 1;
    case FAMILY = 2;
    case BUSINESS = 3;
    case FRIENDS = 4;
    case OTHER = 5;

    public function label(): string
    {
        return match($this) {
            self::SOLO => 'Một mình',
            self::COUPLE => 'Cặp đôi',
            self::FAMILY => 'Gia đình',
            self::BUSINESS => 'Công tác',
            self::FRIENDS => 'Bạn bè',
            self::OTHER => 'Khác',
        };
    }
}
