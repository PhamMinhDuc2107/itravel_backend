<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminRefreshTokenRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'admin_id' => ['required', 'integer', 'min:1'],
            'refresh_token' => ['required', 'string', 'min:32'],
            'context' => ['sometimes', 'array'],
        ];
    }
}
