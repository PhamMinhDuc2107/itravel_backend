<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertAmenityDTO
{
    public function __construct(
        public string $name,
        public ?string $icon,
        public ?string $type,
        public bool $isActive,
        public int $sortOrder,
    ) {}

    /** @return array<string, mixed> */
    public function toPayload(): array
    {
        return [
            'name' => $this->name,
            'icon' => $this->icon,
            'type' => $this->type,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
        ];
    }
}
