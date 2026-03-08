<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\TourNoteRepositoryInterface;
use App\Infrastructure\Database\Models\TourNoteModel;

final class TourNoteRepository implements TourNoteRepositoryInterface
{
    public function syncByTourId(int $tourId, array $tourNotes): void
    {
        $rows = [];

        foreach ($tourNotes as $item) {
            if (!is_array($item)) {
                continue;
            }

            $title = isset($item['title']) && is_string($item['title']) ? trim($item['title']) : '';
            if ($title === '') {
                continue;
            }

            $rows[] = [
                'tour_id' => $tourId,
                'title' => $title,
                'content' => isset($item['content']) && is_string($item['content']) && $item['content'] !== '' ? $item['content'] : null,
                'sort' => isset($item['sort']) ? (int) $item['sort'] : 0,
            ];
        }

        TourNoteModel::query()->where('tour_id', $tourId)->delete();

        if ($rows !== []) {
            TourNoteModel::query()->insert($rows);
        }
    }
}
