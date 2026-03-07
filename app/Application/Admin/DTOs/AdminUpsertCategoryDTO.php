<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertCategoryDTO
{
    public function __construct(
        public ?int $parentId,
        public string $name,
        public ?string $type,
        public ?string $description,
        public int $sort,
        public bool $isActive,
        public bool $isFeatured,
        public ?string $metaTitle,
        public ?string $metaDescription,
    ) {}

    /** @return array<string, mixed> */
    public function toPayload(): array
    {
        return [
            'parent_id' => $this->parentId,
            'name' => $this->name,
            'type' => $this->type,
            'description' => $this->description,
            'sort' => $this->sort,
            'is_active' => $this->isActive,
            'is_featured' => $this->isFeatured,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
        ];
    }
}
