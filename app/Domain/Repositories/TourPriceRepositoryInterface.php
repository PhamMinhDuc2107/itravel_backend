<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface TourPriceRepositoryInterface
{
    /** @param array<int, array<string, mixed>> $tourPrices */
    public function syncByTourId(int $tourId, array $tourPrices): void;
}
