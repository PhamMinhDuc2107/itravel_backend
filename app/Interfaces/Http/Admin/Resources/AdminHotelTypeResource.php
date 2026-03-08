<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

final class AdminHotelTypeResource extends BaseResource
{
    /** @return array<string, mixed> */
    protected function data(Request $request): ?array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'name' => data_get($resource, 'name'),
            'slug' => data_get($resource, 'slug'),
            'icon' => data_get($resource, 'icon'),
            'is_active' => (bool) data_get($resource, 'is_active', true),
            'sort' => (int) data_get($resource, 'sort', 0),
            'created_at' => data_get($resource, 'created_at'),
            'updated_at' => data_get($resource, 'updated_at'),
        ];
    }
}
