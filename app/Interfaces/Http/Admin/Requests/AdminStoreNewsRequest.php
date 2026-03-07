<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminStoreNewsRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'news_category_id' => ['required', 'integer', 'exists:news_categories,id'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
            'title' => ['required', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'status' => ['sometimes', 'string', 'max:50'],
            'is_featured' => ['sometimes', 'boolean'],
            'view_count' => ['sometimes', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'published_at' => ['nullable', 'date'],
        ];
    }
}
