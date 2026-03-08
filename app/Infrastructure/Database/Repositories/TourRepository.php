<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\TourRepositoryInterface;
use App\Infrastructure\Database\Models\TourModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class TourRepository extends BaseRepository implements TourRepositoryInterface
{
    public function __construct(TourModel $model)
    {
        parent::__construct($model);
    }

    public function paginateForAdmin(
        int $page,
        int $perPage,
        ?string $search,
        ?string $searchBy,
        ?int $categoryId,
        ?string $status,
        ?bool $isFeatured,
        ?bool $isHot,
    ): array {
        $query = $this->newQuery()
            ->with(['category:id,name,slug'])
            ->orderByDesc('id');

        if ($search !== null && $search !== '') {
            $searchableColumns = TourModel::searchableColumns();
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

        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        if ($isFeatured !== null) {
            $query->where('is_featured', $isFeatured);
        }

        if ($isHot !== null) {
            $query->where('is_hot', $isHot);
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
            'category:id,name,slug',
            'images:id,tour_id,url,alt,is_cover,sort',
            'itineraries:id,tour_id,day_number,title,content',
            'notes:id,tour_id,title,content,sort',
            'prices:id,tour_id,passenger_type,price,currency,includes,excludes',
            'schedules:id,tour_id,departure_date,return_date,max_slots,booked_slots,status,note',
            'priceOverrides:id,tour_id,tour_schedule_id,departure_date,adult_price,child_price,infant_price,is_active,note',
            'tourLocations:id,tour_id,location_id,role,sort',
            'tourLocations.location:id,name,slug',
            'locations:id,name,slug',
        ]);

        return $model?->toArray();
    }

    public function createAndLoad(array $payload): array
    {
        $model = parent::create($payload);

        return (array) $this->newQuery()
            ->with([
                'category:id,name,slug',
                'images:id,tour_id,url,alt,is_cover,sort',
                'itineraries:id,tour_id,day_number,title,content',
                'notes:id,tour_id,title,content,sort',
                'prices:id,tour_id,passenger_type,price,currency,includes,excludes',
                'schedules:id,tour_id,departure_date,return_date,max_slots,booked_slots,status,note',
                'priceOverrides:id,tour_id,tour_schedule_id,departure_date,adult_price,child_price,infant_price,is_active,note',
                'tourLocations:id,tour_id,location_id,role,sort',
                'tourLocations.location:id,name,slug',
                'locations:id,name,slug',
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
                'category:id,name,slug',
                'images:id,tour_id,url,alt,is_cover,sort',
                'itineraries:id,tour_id,day_number,title,content',
                'notes:id,tour_id,title,content,sort',
                'prices:id,tour_id,passenger_type,price,currency,includes,excludes',
                'schedules:id,tour_id,departure_date,return_date,max_slots,booked_slots,status,note',
                'priceOverrides:id,tour_id,tour_schedule_id,departure_date,adult_price,child_price,infant_price,is_active,note',
                'tourLocations:id,tour_id,location_id,role,sort',
                'tourLocations.location:id,name,slug',
                'locations:id,name,slug',
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
