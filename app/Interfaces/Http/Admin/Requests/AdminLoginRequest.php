<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminLoginRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'max:255'],
            'context' => ['sometimes', 'array'],
        ];
    }
}
