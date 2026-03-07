<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\HotelTypeRepositoryInterface;
use App\Infrastructure\Database\Models\HotelTypeModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class HotelTypeRepository extends BaseRepository implements HotelTypeRepositoryInterface
{
    public function __construct(HotelTypeModel $model)
    {
        parent::__construct($model);
    }

    public function paginateForAdmin(
        int $page,
        int $perPage,
        ?string $search,
        ?string $searchBy,
        ?bool $isActive,
    ): array {
        $query = $this->newQuery()->orderByDesc('id');

        if ($search !== null && $search !== '') {
            $searchableColumns = HotelTypeModel::searchableColumns();
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

        if ($isActive !== null) {
            $query->where('is_active', $isActive);
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

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
        return parent::findById($id)?->toArray();
    }

    public function createAndLoad(array $payload): array
    {
        $model = parent::create($payload);

        return (array) $this->newQuery()->findOrFail($model->getKey())->toArray();
    }

    public function updateAndLoadById(int $id, array $payload): ?array
    {
        if (parent::findById($id) === null) {
            return null;
        }

        parent::update($id, $payload);

        return (array) $this->newQuery()->findOrFail($id)->toArray();
    }

    public function deleteExistingById(int $id): bool
    {
        if (parent::findById($id) === null) {
            return false;
        }

        return parent::delete($id);
    }
}
