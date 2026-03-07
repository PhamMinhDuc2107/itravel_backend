<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertServicePageDTO
{
    public function __construct(
        public int $categoryId,
        public string $name,
        public ?string $thumbnail,
        public ?string $excerpt,
        public string $content,
        public bool $isFeatured,
        public bool $isActive,
        public int $sortOrder,
        public ?string $metaTitle,
        public ?string $metaDescription,
        public ?int $actorId,
    ) {}

    /** @return array<string, mixed> */
    public function toPayload(bool $isUpdate = false): array
    {
        $payload = [
            'category_id' => $this->categoryId,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'is_featured' => $this->isFeatured,
            'is_active' => $this->isActive,
            'sort_order' => $this->sortOrder,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
        ];

        if ($isUpdate) {
            $payload['updated_by'] = $this->actorId;

            return $payload;
        }

        $payload['created_by'] = $this->actorId;

        return $payload;
    }
}
