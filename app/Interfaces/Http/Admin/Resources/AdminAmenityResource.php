<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

final class AdminAmenityResource extends BaseResource
{
    /** @return array<string, mixed> */
    protected function data(Request $request): ?array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'name' => data_get($resource, 'name'),
            'icon' => data_get($resource, 'icon'),
            'type' => data_get($resource, 'type'),
            'is_active' => (bool) data_get($resource, 'is_active', true),
            'sort_order' => (int) data_get($resource, 'sort_order', 0),
            'created_at' => data_get($resource, 'created_at'),
            'updated_at' => data_get($resource, 'updated_at'),
        ];
    }
}
