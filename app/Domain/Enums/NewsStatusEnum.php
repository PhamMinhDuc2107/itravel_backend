<?php

namespace App\Domain\Enums;

enum NewsStatusEnum: int
{
    case DRAFT = 0;
    case PUBLISHED = 1;
    case ARCHIVED = 2;

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Bản nháp',
            self::PUBLISHED => 'Đã xuất bản',
            self::ARCHIVED => 'Lưu trữ',
        };
    }

    public function isDraft(): bool
    {
        return $this === self::DRAFT;
    }

    public function isPublished(): bool
    {
        return $this === self::PUBLISHED;
    }

    public function isArchived(): bool
    {
        return $this === self::ARCHIVED;
    }
}
