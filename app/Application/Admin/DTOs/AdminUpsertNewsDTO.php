<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertNewsDTO
{
    public function __construct(
        public int $newsCategoryId,
        public ?int $authorId,
        public string $title,
        public ?string $thumbnail,
        public ?string $excerpt,
        public string $content,
        public string $status,
        public bool $isFeatured,
        public int $viewCount,
        public ?string $metaTitle,
        public ?string $metaDescription,
        public ?string $publishedAt,
    ) {}

    /** @return array<string, mixed> */
    public function toPayload(): array
    {
        return [
            'news_category_id' => $this->newsCategoryId,
            'author_id' => $this->authorId,
            'title' => $this->title,
            'thumbnail' => $this->thumbnail,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'status' => $this->status,
            'is_featured' => $this->isFeatured,
            'view_count' => $this->viewCount,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
            'published_at' => $this->publishedAt,
        ];
    }
}
