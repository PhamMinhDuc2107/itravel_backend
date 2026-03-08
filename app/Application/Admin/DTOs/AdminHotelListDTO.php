<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminHotelListDTO
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $search,
        public ?string $searchBy,
        public ?int $locationId,
        public ?int $hotelTypeId,
        public ?bool $isActive,
        public ?bool $isFeatured,
    ) {}
}
