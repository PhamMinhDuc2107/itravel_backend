<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertNewsCategoryDTO
{
    public function __construct(
        public ?int $parentId,
        public string $name,
        public ?string $thumbnail,
        public ?string $description,
        public bool $isActive,
        public int $sort,
    ) {}

    /** @return array<string, mixed> */
    public function toPayload(): array
    {
        return [
            'parent_id' => $this->parentId,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'description' => $this->description,
            'is_active' => $this->isActive,
            'sort' => $this->sort,
        ];
    }
}
