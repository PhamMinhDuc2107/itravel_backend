<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\TourPriceRepositoryInterface;
use App\Infrastructure\Database\Models\TourPriceModel;

final class TourPriceRepository implements TourPriceRepositoryInterface
{
    public function syncByTourId(int $tourId, array $tourPrices): void
    {
        $rows = [];

        foreach ($tourPrices as $item) {
            if (!is_array($item)) {
                continue;
            }

            $passengerType = isset($item['passenger_type']) ? (int) $item['passenger_type'] : -1;
            $price = isset($item['price']) ? (int) $item['price'] : -1;
            if ($passengerType < 0 || $price < 0) {
                continue;
            }

            $rows[] = [
                'tour_id' => $tourId,
                'passenger_type' => $passengerType,
                'price' => $price,
                'currency' => isset($item['currency']) && is_string($item['currency']) && $item['currency'] !== '' ? $item['currency'] : 'VND',
                'includes' => isset($item['includes']) && is_string($item['includes']) && $item['includes'] !== '' ? $item['includes'] : null,
                'excludes' => isset($item['excludes']) && is_string($item['excludes']) && $item['excludes'] !== '' ? $item['excludes'] : null,
            ];
        }

        TourPriceModel::query()->where('tour_id', $tourId)->delete();

        if ($rows !== []) {
            TourPriceModel::query()->insert($rows);
        }
    }
}
