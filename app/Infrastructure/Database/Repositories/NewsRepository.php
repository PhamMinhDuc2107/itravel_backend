<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\NewsRepositoryInterface;
use App\Infrastructure\Database\Models\NewsModel;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class NewsRepository extends BaseRepository implements NewsRepositoryInterface
{
    public function __construct(NewsModel $model)
    {
        parent::__construct($model);
    }

    public function paginateForAdmin(
        int $page,
        int $perPage,
        ?string $search,
        ?string $searchBy,
        ?int $newsCategoryId,
        ?string $status,
        ?bool $isFeatured,
    ): array {
        $query = $this->newQuery()
            ->with([
                'category:id,name,slug',
                'author:id,name,email',
            ])
            ->orderByDesc('id');

        if ($search !== null && $search !== '') {
            $searchableColumns = NewsModel::searchableColumns();
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

        if ($newsCategoryId !== null) {
            $query->where('news_category_id', $newsCategoryId);
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
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
        $model = parent::findById($id, ['category:id,name,slug', 'author:id,name,email']);

        return $model?->toArray();
    }

    public function createAndLoad(array $payload): array
    {
        $model = parent::create($payload);

        return (array) $this->newQuery()
            ->with(['category:id,name,slug', 'author:id,name,email'])
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
            ->with(['category:id,name,slug', 'author:id,name,email'])
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
