<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\TourPriceOverrideRepositoryInterface;
use App\Infrastructure\Database\Models\TourPriceOverrideModel;

final class TourPriceOverrideRepository implements TourPriceOverrideRepositoryInterface
{
    public function syncByTourId(int $tourId, array $tourPriceOverrides): void
    {
        $rows = [];

        foreach ($tourPriceOverrides as $item) {
            if (!is_array($item)) {
                continue;
            }

            $tourScheduleId = isset($item['tour_schedule_id']) ? (int) $item['tour_schedule_id'] : 0;
            $departureDate = isset($item['departure_date']) ? (string) $item['departure_date'] : '';
            if ($tourScheduleId <= 0 || $departureDate === '') {
                continue;
            }

            $rows[] = [
                'tour_id' => $tourId,
                'tour_schedule_id' => $tourScheduleId,
                'departure_date' => $departureDate,
                'adult_price' => isset($item['adult_price']) ? (int) $item['adult_price'] : null,
                'child_price' => isset($item['child_price']) ? (int) $item['child_price'] : null,
                'infant_price' => isset($item['infant_price']) ? (int) $item['infant_price'] : null,
                'is_active' => isset($item['is_active']) ? (bool) $item['is_active'] : true,
                'note' => isset($item['note']) && is_string($item['note']) && $item['note'] !== '' ? $item['note'] : null,
            ];
        }

        TourPriceOverrideModel::query()->where('tour_id', $tourId)->delete();

        if ($rows !== []) {
            TourPriceOverrideModel::query()->insert($rows);
        }
    }
}
