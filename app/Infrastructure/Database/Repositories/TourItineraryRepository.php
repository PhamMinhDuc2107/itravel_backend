<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\TourItineraryRepositoryInterface;
use App\Infrastructure\Database\Models\TourItineraryModel;

final class TourItineraryRepository implements TourItineraryRepositoryInterface
{
    public function syncByTourId(int $tourId, array $tourItineraries): void
    {
        $rows = [];

        foreach ($tourItineraries as $item) {
            if (!is_array($item)) {
                continue;
            }

            $dayNumber = isset($item['day_number']) ? (int) $item['day_number'] : 0;
            $title = isset($item['title']) && is_string($item['title']) ? trim($item['title']) : '';
            if ($dayNumber <= 0 || $title === '') {
                continue;
            }

            $rows[] = [
                'tour_id' => $tourId,
                'day_number' => $dayNumber,
                'title' => $title,
                'content' => isset($item['content']) && is_string($item['content']) && $item['content'] !== '' ? $item['content'] : null,
            ];
        }

        TourItineraryModel::query()->where('tour_id', $tourId)->delete();

        if ($rows !== []) {
            TourItineraryModel::query()->insert($rows);
        }
    }
}
