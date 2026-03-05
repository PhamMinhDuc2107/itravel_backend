<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminLogoutRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'refresh_token' => ['nullable', 'string', 'min:32'],
        ];
    }
}
