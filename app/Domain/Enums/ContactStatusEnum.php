<?php

namespace App\Domain\Enums;

enum ContactStatusEnum: int
{
    case NEW = 0;
    case PROCESSING = 1;
    case RESOLVED = 2;
    case SPAM = 3;

    public function label(): string
    {
        return match($this) {
            self::NEW => 'Mới',
            self::PROCESSING => 'Đang xử lý',
            self::RESOLVED => 'Đã giải quyết',
            self::SPAM => 'Spam',
        };
    }

    public function isNew(): bool
    {
        return $this === self::NEW;
    }

    public function isProcessing(): bool
    {
        return $this === self::PROCESSING;
    }

    public function isResolved(): bool
    {
        return $this === self::RESOLVED;
    }

    public function isSpam(): bool
    {
        return $this === self::SPAM;
    }
}
