<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminLocationListDTO
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $search,
        public ?string $searchBy,
        public ?int $parentId,
        public ?bool $isActive,
        public ?bool $isFeatured,
        public ?bool $isDomestic,
        public ?string $type,
    ) {}
}
