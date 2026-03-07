<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

final class AdminLocationResource extends BaseResource
{
    /** @return array<string, mixed> */
    protected function data(Request $request): ?array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'parent_id' => data_get($resource, 'parent_id'),
            'name' => data_get($resource, 'name'),
            'slug' => data_get($resource, 'slug'),
            'type' => data_get($resource, 'type'),
            'code' => data_get($resource, 'code'),
            'thumbnail' => data_get($resource, 'thumbnail'),
            'banner' => data_get($resource, 'banner'),
            'description' => data_get($resource, 'description'),
            'is_active' => (bool) data_get($resource, 'is_active', true),
            'is_featured' => (bool) data_get($resource, 'is_featured', false),
            'is_domestic' => (bool) data_get($resource, 'is_domestic', false),
            'sort_order' => (int) data_get($resource, 'sort_order', 0),
            'latitude' => data_get($resource, 'latitude'),
            'longitude' => data_get($resource, 'longitude'),
            'meta_title' => data_get($resource, 'meta_title'),
            'meta_description' => data_get($resource, 'meta_description'),
            'created_at' => data_get($resource, 'created_at'),
            'updated_at' => data_get($resource, 'updated_at'),
            'parent' => data_get($resource, 'parent') !== null ? [
                'id' => data_get($resource, 'parent.id'),
                'name' => data_get($resource, 'parent.name'),
                'slug' => data_get($resource, 'parent.slug'),
            ] : null,
        ];
    }
}
