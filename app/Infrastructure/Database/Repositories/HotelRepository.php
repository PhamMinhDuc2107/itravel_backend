<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\HotelRepositoryInterface;
use App\Infrastructure\Database\Models\HotelModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

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
        $model = parent::create($payload);

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
        parent::update($id, $payload);

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
}
