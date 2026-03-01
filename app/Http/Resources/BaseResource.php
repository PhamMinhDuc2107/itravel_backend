<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    public function toArray($request): array
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
