<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface TourNoteRepositoryInterface
{
    /** @param array<int, array<string, mixed>> $tourNotes */
    public function syncByTourId(int $tourId, array $tourNotes): void;
}
