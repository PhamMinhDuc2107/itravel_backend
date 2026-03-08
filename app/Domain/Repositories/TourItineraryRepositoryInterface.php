<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface TourItineraryRepositoryInterface
{
    /** @param array<int, array<string, mixed>> $tourItineraries */
    public function syncByTourId(int $tourId, array $tourItineraries): void;
}
