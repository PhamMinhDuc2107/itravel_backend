<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\TourImageRepositoryInterface;
use App\Infrastructure\Database\Models\TourImageModel;

final class TourImageRepository implements TourImageRepositoryInterface
{
    public function syncByTourId(int $tourId, array $tourImages): void
    {
        $rows = [];

        foreach ($tourImages as $item) {
            if (!is_array($item)) {
                continue;
            }

            $url = isset($item['url']) && is_string($item['url']) ? trim($item['url']) : '';
            if ($url === '') {
                continue;
            }

            $rows[] = [
                'tour_id' => $tourId,
                'url' => $url,
                'alt' => isset($item['alt']) && is_string($item['alt']) && $item['alt'] !== '' ? $item['alt'] : null,
                'is_cover' => isset($item['is_cover']) ? (bool) $item['is_cover'] : false,
                'sort' => isset($item['sort']) ? (int) $item['sort'] : 0,
            ];
        }

        TourImageModel::query()->where('tour_id', $tourId)->delete();

        if ($rows !== []) {
            TourImageModel::query()->insert($rows);
        }
    }
}
