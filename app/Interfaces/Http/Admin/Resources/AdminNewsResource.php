<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

final class AdminNewsResource extends BaseResource
{
    /** @return array<string, mixed> */
    protected function data(Request $request): ?array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'news_category_id' => data_get($resource, 'news_category_id'),
            'author_id' => data_get($resource, 'author_id'),
            'title' => data_get($resource, 'title'),
            'slug' => data_get($resource, 'slug'),
            'thumbnail' => data_get($resource, 'thumbnail'),
            'excerpt' => data_get($resource, 'excerpt'),
            'content' => data_get($resource, 'content'),
            'status' => data_get($resource, 'status'),
            'is_featured' => (bool) data_get($resource, 'is_featured', false),
            'view_count' => (int) data_get($resource, 'view_count', 0),
            'meta_title' => data_get($resource, 'meta_title'),
            'meta_description' => data_get($resource, 'meta_description'),
            'published_at' => data_get($resource, 'published_at'),
            'created_at' => data_get($resource, 'created_at'),
            'updated_at' => data_get($resource, 'updated_at'),
            'category' => data_get($resource, 'category') !== null ? [
                'id' => data_get($resource, 'category.id'),
                'name' => data_get($resource, 'category.name'),
                'slug' => data_get($resource, 'category.slug'),
            ] : null,
            'author' => data_get($resource, 'author') !== null ? [
                'id' => data_get($resource, 'author.id'),
                'name' => data_get($resource, 'author.name'),
                'email' => data_get($resource, 'author.email'),
            ] : null,
        ];
    }
}
