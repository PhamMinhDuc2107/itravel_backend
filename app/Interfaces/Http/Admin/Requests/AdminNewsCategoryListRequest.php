<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminNewsCategoryListRequest extends BaseAdminRequest
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
            'parent_id' => ['sometimes', 'integer', 'exists:news_categories,id'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
