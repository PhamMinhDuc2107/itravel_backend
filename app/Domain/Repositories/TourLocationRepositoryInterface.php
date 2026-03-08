<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface TourLocationRepositoryInterface
{
    /** @param array<int, array<string, mixed>> $tourLocations */
    public function syncByTourId(int $tourId, array $tourLocations): void;
}
