<?php

namespace App\Domain\Enums;

enum LocationRoleEnum: int
{
    case DEPARTURE = 0;
    case DESTINATION = 1;
    case TRANSIT = 2;

    public function label(): string
    {
        return match($this) {
            self::DEPARTURE => 'Điểm khởi hành',
            self::DESTINATION => 'Điểm đến',
            self::TRANSIT => 'Điểm trung chuyển',
        };
    }

    public function isDeparture(): bool
    {
        return $this === self::DEPARTURE;
    }

    public function isDestination(): bool
    {
        return $this === self::DESTINATION;
    }

    public function isTransit(): bool
    {
        return $this === self::TRANSIT;
    }
}
