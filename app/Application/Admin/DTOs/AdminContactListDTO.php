<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminContactListDTO
{
    public function __construct(
        public int $page,
        public int $perPage,
        public ?string $search,
        public ?string $searchBy,
        public ?string $status,
        public ?int $resolvedBy,
    ) {}
}
