<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface HotelImageRepositoryInterface
{
    /** @param array<int, array<string, mixed>> $hotelImages */
    public function syncByHotelId(int $hotelId, array $hotelImages): void;
}
