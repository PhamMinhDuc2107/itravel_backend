<?php

namespace App\Repositories\Contracts;

use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface RepositoryInterface
{
    public function getAll(array $filters = [], array $relations = [], array $columns = ['*']): Collection;

    public function findById(int $id, array $relations = [], array $columns = ['*']): ?Model;

    public function findByField(string $field, mixed $value, array $relations = []): ?Model;

    public function findAllByField(string $field, mixed $value, array $relations = []): Collection;

    public function findByConditions(array $conditions, array $relations = [], array $columns = ['*']): ?Model;

    public function findAllByConditions(array $conditions, array $relations = [], array $columns = ['*']): Collection;

    public function paginate(int $perPage = 15, array $filters = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator;

    public function simplePaginate(int $perPage = 15, array $filters = [], array $relations = []): Paginator;

    public function count(array $filters = []): int;

    public function exists(array $conditions): bool;

    public function max(string $column, array $filters = []): mixed;

    public function min(string $column, array $filters = []): mixed;

    public function sum(string $column, array $filters = []): int|float;

    public function pluck(string $column, ?string $keyColumn = null, array $filters = []): Collection;

    public function create(array $data): Model;

    public function createMany(array $records): Collection;

    public function update(int $id, array $data): Model;

    public function updateByConditions(array $conditions, array $data): bool;

    public function updateOrCreate(array $conditions, array $data): Model;

    public function firstOrCreate(array $conditions, array $data): Model;

    public function delete(int $id): bool;

    public function deleteByConditions(array $conditions): bool;

    public function softDelete(int $id): bool;

    public function restore(int $id): bool;

    public function forceDelete(int $id): bool;

    public function with(array $relations): static;

    public function withCount(array $relations): static;

    public function has(string $relation): static;

    public function whereHas(string $relation, Closure $callback): static;

    public function findWithLock(int $id): ?Model;

    public function findByConditionsWithLock(array $conditions): ?Model;

    public function orderBy(string $column, string $direction = 'asc'): static;

    public function latest(string $column = 'created_at'): static;

    public function oldest(string $column = 'created_at'): static;

    public function chunk(int $size, Closure $callback, array $filters = []): void;

    public function chunkById(int $size, Closure $callback, array $filters = []): void;
}
