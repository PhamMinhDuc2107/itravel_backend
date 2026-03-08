<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\TourLocationRepositoryInterface;
use App\Infrastructure\Database\Models\TourLocationModel;

final class TourLocationRepository implements TourLocationRepositoryInterface
{
    public function syncByTourId(int $tourId, array $tourLocations): void
    {
        $rows = [];

        foreach ($tourLocations as $item) {
            if (!is_array($item)) {
                continue;
            }

            $locationId = isset($item['location_id']) ? (int) $item['location_id'] : 0;
            if ($locationId <= 0) {
                continue;
            }

            $rows[] = [
                'tour_id' => $tourId,
                'location_id' => $locationId,
                'role' => isset($item['role']) ? (int) $item['role'] : 1,
                'sort' => isset($item['sort']) ? (int) $item['sort'] : 0,
            ];
        }

        TourLocationModel::query()->where('tour_id', $tourId)->delete();

        if ($rows !== []) {
            TourLocationModel::query()->insert($rows);
        }
    }
}
