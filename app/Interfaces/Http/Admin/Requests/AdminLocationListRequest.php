<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminLocationListRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'search' => ['sometimes', 'string', 'max:255'],
            'search_by' => ['sometimes', 'string', 'max:50'],
            'parent_id' => ['sometimes', 'integer', 'exists:locations,id'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_domestic' => ['sometimes', 'boolean'],
            'type' => ['sometimes', 'string', 'max:50'],
        ];
    }
}
