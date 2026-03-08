<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertLocationDTO
{
    public function __construct(
        public ?int $parentId,
        public string $name,
        public ?string $type,
        public ?string $code,
        public ?string $thumbnail,
        public ?string $banner,
        public ?string $description,
        public bool $isActive,
        public bool $isFeatured,
        public bool $isDomestic,
        public int $sortOrder,
        public ?string $latitude,
        public ?string $longitude,
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
            'code' => $this->code,
            'thumbnail' => $this->thumbnail,
            'banner' => $this->banner,
            'description' => $this->description,
            'is_active' => $this->isActive,
            'is_featured' => $this->isFeatured,
            'is_domestic' => $this->isDomestic,
            'sort_order' => $this->sortOrder,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
        ];
    }
}
