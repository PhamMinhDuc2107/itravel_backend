<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

use App\Domain\Entities\AdminEntity;
use App\Domain\Enums\AdminAuthTokenTypeEnum;

readonly final class AdminAuthResultDTO
{
    public function __construct(
        public AdminEntity $admin,
        public string $accessToken,
        public ?string $refreshToken,
        public ?string $refreshExpiresAt,
        public ?int $expiresIn,
        public AdminAuthTokenTypeEnum $tokenType = AdminAuthTokenTypeEnum::BEARER,
    ) {}

    /**
     * @return array{
     *     admin: AdminEntity,
     *     access_token: string,
     *     refresh_token: ?string,
     *     refresh_expires_at: ?string,
     *     expires_in: ?int,
     *     token_type: string
     * }
     */
    public function toArray(): array
    {
        return [
            'admin' => $this->admin,
            'access_token' => $this->accessToken,
            'refresh_token' => $this->refreshToken,
            'refresh_expires_at' => $this->refreshExpiresAt,
            'expires_in' => $this->expiresIn,
            'token_type' => $this->tokenType->value,
        ];
    }
}
