<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface TourImageRepositoryInterface
{
    /** @param array<int, array<string, mixed>> $tourImages */
    public function syncByTourId(int $tourId, array $tourImages): void;
}
