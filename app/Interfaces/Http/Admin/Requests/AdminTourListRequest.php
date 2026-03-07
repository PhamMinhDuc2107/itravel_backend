<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminTourListRequest extends BaseAdminRequest
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
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'status' => ['sometimes', 'string', 'max:50'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_hot' => ['sometimes', 'boolean'],
        ];
    }
}
