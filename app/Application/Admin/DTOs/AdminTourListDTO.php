<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminTourListDTO
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $search,
        public ?string $searchBy,
        public ?int $categoryId,
        public ?string $status,
        public ?bool $isFeatured,
        public ?bool $isHot,
    ) {}
}
