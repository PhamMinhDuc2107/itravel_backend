<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\TourScheduleRepositoryInterface;
use App\Infrastructure\Database\Models\TourScheduleModel;

final class TourScheduleRepository implements TourScheduleRepositoryInterface
{
    public function syncByTourId(int $tourId, array $tourSchedules): void
    {
        $rows = [];

        foreach ($tourSchedules as $item) {
            if (!is_array($item)) {
                continue;
            }

            $departureDate = isset($item['departure_date']) && is_string($item['departure_date']) ? trim($item['departure_date']) : '';
            if ($departureDate === '') {
                continue;
            }

            $rows[] = [
                'tour_id' => $tourId,
                'departure_date' => $departureDate,
                'return_date' => isset($item['return_date']) && is_string($item['return_date']) && $item['return_date'] !== '' ? $item['return_date'] : null,
                'max_slots' => isset($item['max_slots']) ? (int) $item['max_slots'] : 0,
                'booked_slots' => isset($item['booked_slots']) ? (int) $item['booked_slots'] : 0,
                'status' => isset($item['status']) ? (int) $item['status'] : 0,
                'note' => isset($item['note']) && is_string($item['note']) && $item['note'] !== '' ? $item['note'] : null,
            ];
        }

        TourScheduleModel::query()->where('tour_id', $tourId)->delete();

        if ($rows !== []) {
            TourScheduleModel::query()->insert($rows);
        }
    }
}
