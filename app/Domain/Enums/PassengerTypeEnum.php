<?php

namespace App\Domain\Enums;

enum PassengerTypeEnum: int
{
    case ADULT = 0;
    case CHILD = 1;
    case INFANT = 2;

    public function label(): string
    {
        return match($this) {
            self::ADULT => 'Người lớn',
            self::CHILD => 'Trẻ em',
            self::INFANT => 'Em bé',
        };
    }

    public function isAdult(): bool
    {
        return $this === self::ADULT;
    }

    public function isChild(): bool
    {
        return $this === self::CHILD;
    }

    public function isInfant(): bool
    {
        return $this === self::INFANT;
    }
}
