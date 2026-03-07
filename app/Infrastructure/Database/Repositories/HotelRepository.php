<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\HotelRepositoryInterface;
use App\Infrastructure\Database\Models\HotelModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

final class HotelRepository extends BaseRepository implements HotelRepositoryInterface
{
    public function __construct(HotelModel $model)
    {
        parent::__construct($model);
    }

    public function paginateForAdmin(
        int $page,
        int $perPage,
        ?string $search,
        ?string $searchBy,
        ?int $locationId,
        ?int $hotelTypeId,
        ?bool $isActive,
        ?bool $isFeatured,
    ): array {
        $query = $this->newQuery()
            ->with(['location:id,name,slug', 'hotelType:id,name'])
            ->orderByDesc('id');

        if ($search !== null && $search !== '') {
            $searchableColumns = HotelModel::searchableColumns();
            $normalizedSearchBy = $searchBy !== null ? trim($searchBy) : null;

            if ($normalizedSearchBy !== null && in_array($normalizedSearchBy, $searchableColumns, true)) {
                $query->where($normalizedSearchBy, 'like', "%{$search}%");
            } else {
                $query->where(function ($builder) use ($search, $searchableColumns): void {
                    foreach ($searchableColumns as $index => $column) {
                        if ($index === 0) {
                            $builder->where($column, 'like', "%{$search}%");

                            continue;
                        }

                        $builder->orWhere($column, 'like', "%{$search}%");
                    }
                });
            }
        }

        if ($locationId !== null) {
            $query->where('location_id', $locationId);
        }

        if ($hotelTypeId !== null) {
            $query->where('hotel_type_id', $hotelTypeId);
        }

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        if ($isFeatured !== null) {
            $query->where('is_featured', $isFeatured);
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->paginate(
            perPage: $perPage,
            columns: ['*'],
            pageName: 'page',
            page: $page,
        );

        return [
            'items' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    public function findDetailById(int $id): ?array
    {
        $model = parent::findById($id, [
            'location:id,name,slug',
            'hotelType:id,name',
            'images:id,hotel_id,url,alt,is_cover,sort_order',
            'amenities:id,name,icon,type',
        ]);

        return $model?->toArray();
    }

    public function createAndLoad(array $payload): array
    {
        $hasHotelImages = array_key_exists('hotel_images', $payload);
        $hotelImages = $hasHotelImages ? (array) ($payload['hotel_images'] ?? []) : [];

        $hasAmenityIds = array_key_exists('amenity_ids', $payload);
        $amenityIds = $hasAmenityIds ? (array) ($payload['amenity_ids'] ?? []) : [];

        unset($payload['hotel_images'], $payload['amenity_ids']);

        $model = parent::create($payload);

        if ($hasHotelImages) {
            $this->syncHotelImages($model, $hotelImages);
        }

        if ($hasAmenityIds) {
            $this->syncHotelAmenities($model, $amenityIds);
        }

        return (array) $this->newQuery()
            ->with([
                'location:id,name,slug',
                'hotelType:id,name',
                'images:id,hotel_id,url,alt,is_cover,sort_order',
                'amenities:id,name,icon,type',
            ])
            ->findOrFail($model->getKey())
            ->toArray();
    }

    public function updateAndLoadById(int $id, array $payload): ?array
    {
        if (parent::findById($id) === null) {
            return null;
        }

        $hasHotelImages = array_key_exists('hotel_images', $payload);
        $hotelImages = $hasHotelImages ? (array) ($payload['hotel_images'] ?? []) : [];

        $hasAmenityIds = array_key_exists('amenity_ids', $payload);
        $amenityIds = $hasAmenityIds ? (array) ($payload['amenity_ids'] ?? []) : [];

        unset($payload['hotel_images'], $payload['amenity_ids']);

        $updated = parent::update($id, $payload);

        if ($hasHotelImages) {
            $this->syncHotelImages($updated, $hotelImages);
        }

        if ($hasAmenityIds) {
            $this->syncHotelAmenities($updated, $amenityIds);
        }

        return (array) $this->newQuery()
            ->with([
                'location:id,name,slug',
                'hotelType:id,name',
                'images:id,hotel_id,url,alt,is_cover,sort_order',
                'amenities:id,name,icon,type',
            ])
            ->findOrFail($id)
            ->toArray();
    }

    public function deleteExistingById(int $id): bool
    {
        if (parent::findById($id) === null) {
            return false;
        }

        return parent::delete($id);
    }

    /** @param array<int, array<string, mixed>> $hotelImages */
    private function syncHotelImages(Model $hotel, array $hotelImages): void
    {
        $normalized = [];
        foreach ($hotelImages as $item) {
            if (!is_array($item)) {
                continue;
            }

            $url = isset($item['url']) && is_string($item['url']) ? trim($item['url']) : '';
            if ($url === '') {
                continue;
            }

            $normalized[] = [
                'url' => $url,
                'alt' => isset($item['alt']) && is_string($item['alt']) && $item['alt'] !== '' ? $item['alt'] : null,
                'is_cover' => isset($item['is_cover']) ? (bool) $item['is_cover'] : false,
                'sort_order' => isset($item['sort_order']) ? (int) $item['sort_order'] : 0,
            ];
        }

        $hotel->images()->delete();
        if ($normalized !== []) {
            $hotel->images()->createMany($normalized);
        }
    }

    /** @param array<int, mixed> $amenityIds */
    private function syncHotelAmenities(Model $hotel, array $amenityIds): void
    {
        $normalized = array_values(array_unique(array_filter(array_map(
            static fn($id): int => (int) $id,
            $amenityIds,
        ), static fn(int $id): bool => $id > 0)));

        $hotel->amenities()->sync($normalized);
    }
}
