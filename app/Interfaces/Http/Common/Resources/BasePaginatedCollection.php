<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Common\Resources;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

abstract class BasePaginatedCollection extends ResourceCollection
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
        return [
            'data' => $this->collection,
            'meta' => $this->buildMeta(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function buildMeta(): array
    {
        return array_merge([
            'version' => config('app.api_version', '1.0.0'),
            'timestamp' => now()->toIso8601String(),
        ], $this->paginationMeta(), $this->seoMeta(), $this->meta);
    }

    /**
     * @return array<string, mixed>
     */
    protected function seoMeta(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    private function paginationMeta(): array
    {
        $resource = $this->resource;

        if ($resource instanceof LengthAwarePaginator) {
            return [
                'pagination' => [
                    'current_page' => $resource->currentPage(),
                    'last_page' => $resource->lastPage(),
                    'per_page' => $resource->perPage(),
                    'total' => $resource->total(),
                    'from' => $resource->firstItem(),
                    'to' => $resource->lastItem(),
                    'links' => [
                        'first' => $resource->url(1),
                        'last' => $resource->url($resource->lastPage()),
                        'prev' => $resource->previousPageUrl(),
                        'next' => $resource->nextPageUrl(),
                    ],
                ],
            ];
        }

        if ($resource instanceof Paginator) {
            return [
                'pagination' => [
                    'current_page' => $resource->currentPage(),
                    'per_page' => $resource->perPage(),
                    'has_more_pages' => $resource->hasMorePages(),
                    'links' => [
                        'prev' => $resource->previousPageUrl(),
                        'next' => $resource->nextPageUrl(),
                    ],
                ],
            ];
        }

        return [];
    }
}
