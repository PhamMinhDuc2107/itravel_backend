<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertHotelTypeDTO
{
    public function __construct(
        public string $name,
        public ?string $icon,
        public bool $isActive,
        public int $sort,
    ) {}

    /** @return array<string, mixed> */
    public function toPayload(): array
    {
        return [
            'name' => $this->name,
            'icon' => $this->icon,
            'is_active' => $this->isActive,
            'sort' => $this->sort,
        ];
    }
}
