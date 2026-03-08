<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface TourScheduleRepositoryInterface
{
    /** @param array<int, array<string, mixed>> $tourSchedules */
    public function syncByTourId(int $tourId, array $tourSchedules): void;
}
