<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

final class AdminServicePageResource extends BaseResource
{
    /** @return array<string, mixed> */
    protected function data(Request $request): ?array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'category_id' => data_get($resource, 'category_id'),
            'name' => data_get($resource, 'name'),
            'slug' => data_get($resource, 'slug'),
            'thumbnail' => data_get($resource, 'thumbnail'),
            'excerpt' => data_get($resource, 'excerpt'),
            'content' => data_get($resource, 'content'),
            'is_featured' => (bool) data_get($resource, 'is_featured', false),
            'is_active' => (bool) data_get($resource, 'is_active', true),
            'sort_order' => (int) data_get($resource, 'sort_order', 0),
            'meta_title' => data_get($resource, 'meta_title'),
            'meta_description' => data_get($resource, 'meta_description'),
            'created_at' => data_get($resource, 'created_at'),
            'updated_at' => data_get($resource, 'updated_at'),
            'category' => data_get($resource, 'category') !== null ? [
                'id' => data_get($resource, 'category.id'),
                'name' => data_get($resource, 'category.name'),
                'slug' => data_get($resource, 'category.slug'),
            ] : null,
        ];
    }
}
