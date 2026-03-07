<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminStoreContactRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'status' => ['sometimes', 'string', 'max:50'],
            'admin_note' => ['nullable', 'string'],
            'resolved_by' => ['nullable', 'integer', 'exists:users,id'],
            'resolved_at' => ['nullable', 'date'],
            'ip_address' => ['nullable', 'ip'],
        ];
    }
}
