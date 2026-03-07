<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

final class AdminCompanySettingResource extends BaseResource
{
    /** @return array<string, mixed> */
    protected function data(Request $request): ?array
    {
        return (array) $this->resource;
    }
}
