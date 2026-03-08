<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface TourPriceOverrideRepositoryInterface
{
    /** @param array<int, array<string, mixed>> $tourPriceOverrides */
    public function syncByTourId(int $tourId, array $tourPriceOverrides): void;
}
