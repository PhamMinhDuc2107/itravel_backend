<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Application\Admin\DTOs\AdminAuthResultDTO;
use App\Domain\Enums\AdminAuthTokenTypeEnum;
use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

class AdminAuthResource extends BaseResource
{
    /**
     * @return array{
     *     token_type: string,
     *     access_token: ?string,
     *     refresh_token: ?string,
     *     refresh_expires_at: ?string,
     *     expires_in: ?int,
     *     admin: AdminProfileResource|null
     * }
     */
    protected function data(Request $request): ?array
    {
        if ($this->resource instanceof AdminAuthResultDTO) {
            return [
                'token_type' => $this->resource->tokenType->value,
                'access_token' => $this->resource->accessToken,
                'refresh_token' => $this->resource->refreshToken,
                'refresh_expires_at' => $this->resource->refreshExpiresAt,
                'expires_in' => $this->resource->expiresIn,
                'admin' => new AdminProfileResource($this->resource->admin),
            ];
        }

        return [
            'token_type' => $this['token_type'] ?? AdminAuthTokenTypeEnum::BEARER->value,
            'access_token' => $this['access_token'] ?? null,
            'refresh_token' => $this['refresh_token'] ?? null,
            'refresh_expires_at' => $this['refresh_expires_at'] ?? null,
            'expires_in' => $this['expires_in'] ?? null,
            'admin' => isset($this['admin']) ? new AdminProfileResource($this['admin']) : null,
        ];
    }
}
