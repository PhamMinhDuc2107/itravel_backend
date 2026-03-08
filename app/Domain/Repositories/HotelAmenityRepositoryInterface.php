<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface HotelAmenityRepositoryInterface
{
    /** @param array<int, mixed> $amenityIds */
    public function syncByHotelId(int $hotelId, array $amenityIds): void;
}
