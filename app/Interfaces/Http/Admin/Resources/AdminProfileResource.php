<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

class AdminProfileResource extends BaseResource
{
    protected function data(Request $request): ?array
    {
        $resource = $this->resource;
        $status = data_get($resource, 'status');

        return [
            'id' => data_get($resource, 'id'),
            'name' => data_get($resource, 'name'),
            'email' => data_get($resource, 'email'),
            'phone' => data_get($resource, 'phone'),
            'avatar' => data_get($resource, 'avatar'),
            'status' => $status instanceof \UnitEnum ? $status->value : $status,
            'last_login_at' => data_get($resource, 'lastLoginAt', data_get($resource, 'last_login_at')),
            'created_at' => data_get($resource, 'createdAt', data_get($resource, 'created_at')),
        ];
    }
}
