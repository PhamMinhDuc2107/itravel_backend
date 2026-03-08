<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\HotelAmenityRepositoryInterface;
use App\Infrastructure\Database\Models\HotelModel;

final class HotelAmenityRepository implements HotelAmenityRepositoryInterface
{
    public function syncByHotelId(int $hotelId, array $amenityIds): void
    {
        $normalized = array_values(array_unique(array_filter(array_map(
            static fn($id): int => (int) $id,
            $amenityIds,
        ), static fn(int $id): bool => $id > 0)));

        $hotel = HotelModel::query()->find($hotelId);
        if ($hotel === null) {
            return;
        }

        $hotel->amenities()->sync($normalized);
    }
}
