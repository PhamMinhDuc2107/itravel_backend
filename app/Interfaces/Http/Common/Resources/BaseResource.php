<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Common\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class BaseResource extends JsonResource
{
    /** @var array<string, mixed> */
    private array $meta = [];

    /**
     * @param array<string, mixed> $meta
     */
    public function withMeta(array $meta): static
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $meta = $this->buildMeta();

        return [
            'data' => $this->normalizeNestedData($this->data($request), $request),
            'meta' => $meta,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildMeta(): array
    {
        return array_merge([
            'version' => env('API_VERSION', '1.0.0'),
            'timestamp' => now()->toIso8601String(),
        ], $this->meta);
    }

    private function normalizeNestedData(mixed $value, Request $request): mixed
    {
        if ($value instanceof self) {
            $resolved = $value->toArray($request);

            if (is_array($resolved) && array_key_exists('data', $resolved)) {
                return $this->normalizeNestedData($resolved['data'], $request);
            }

            return $this->normalizeNestedData($resolved, $request);
        }

        if ($value instanceof JsonResource) {
            return $this->normalizeNestedData($value->resolve($request), $request);
        }

        if (!is_array($value)) {
            return $value;
        }

        $normalized = [];
        foreach ($value as $key => $item) {
            $normalized[$key] = $this->normalizeNestedData($item, $request);
        }

        return $normalized;
    }

    /**
     * @return array<string, mixed>|null
     */
    abstract protected function data(Request $request): ?array;
}
