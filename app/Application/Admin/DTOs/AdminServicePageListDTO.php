<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminServicePageListDTO
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $search,
        public ?string $searchBy,
        public ?int $categoryId,
        public ?bool $isActive,
        public ?bool $isFeatured,
    ) {}
}
