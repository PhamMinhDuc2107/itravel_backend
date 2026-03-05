<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Client\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Interfaces\Http\Common\Resources\BaseResource as CommonBaseResource;

abstract class BaseResource extends CommonBaseResource
{
    protected function data(Request $request): ?array
    {
        return $this->resource->toArray();
    }

    protected function formatDate(?string $field): ?string
    {
        if (!$field || !$this->resource->{$field}) {
            return null;
        }

        return Carbon::parse($this->resource->{$field})->toIso8601String();
    }

    protected function formatMoney(?string $field): ?string
    {
        if (!$field || !isset($this->resource->{$field})) {
            return null;
        }

        return number_format($this->resource->{$field}, 0, ',', '.');
    }
}
