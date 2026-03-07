<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

final class AdminContactResource extends BaseResource
{
    /** @return array<string, mixed> */
    protected function data(Request $request): ?array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'full_name' => data_get($resource, 'full_name'),
            'email' => data_get($resource, 'email'),
            'phone' => data_get($resource, 'phone'),
            'subject' => data_get($resource, 'subject'),
            'message' => data_get($resource, 'message'),
            'status' => data_get($resource, 'status'),
            'admin_note' => data_get($resource, 'admin_note'),
            'resolved_by' => data_get($resource, 'resolved_by'),
            'resolved_at' => data_get($resource, 'resolved_at'),
            'ip_address' => data_get($resource, 'ip_address'),
            'created_at' => data_get($resource, 'created_at'),
            'updated_at' => data_get($resource, 'updated_at'),
            'resolver' => data_get($resource, 'resolver') !== null ? [
                'id' => data_get($resource, 'resolver.id'),
                'name' => data_get($resource, 'resolver.name'),
                'email' => data_get($resource, 'resolver.email'),
            ] : null,
        ];
    }
}
