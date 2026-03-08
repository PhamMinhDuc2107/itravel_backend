<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\HotelImageRepositoryInterface;
use App\Infrastructure\Database\Models\HotelImageModel;

final class HotelImageRepository implements HotelImageRepositoryInterface
{
    public function syncByHotelId(int $hotelId, array $hotelImages): void
    {
        $rows = [];

        foreach ($hotelImages as $item) {
            if (!is_array($item)) {
                continue;
            }

            $url = isset($item['url']) && is_string($item['url']) ? trim($item['url']) : '';
            if ($url === '') {
                continue;
            }

            $rows[] = [
                'hotel_id' => $hotelId,
                'url' => $url,
                'alt' => isset($item['alt']) && is_string($item['alt']) && $item['alt'] !== '' ? $item['alt'] : null,
                'is_cover' => isset($item['is_cover']) ? (bool) $item['is_cover'] : false,
                'sort_order' => isset($item['sort_order']) ? (int) $item['sort_order'] : 0,
            ];
        }

        HotelImageModel::query()->where('hotel_id', $hotelId)->delete();

        if ($rows !== []) {
            HotelImageModel::query()->insert($rows);
        }
    }
}
