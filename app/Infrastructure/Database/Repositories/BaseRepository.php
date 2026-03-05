<?php

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\RepositoryInterface;
use Closure;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

abstract class BaseRepository implements RepositoryInterface
{
    protected Model $model;

    private array $eagerLoads = [];
    private array $eagerCounts = [];
    private ?string $orderColumn = null;
    private string $orderDirection = 'asc';
    private array $hasRelations = [];
    private array $whereHasCallbacks = [];

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * @param array $filters
     * @param array $relations
     * @param array $columns
     * @return Collection
     */
    public function getAll(array $filters = [], array $relations = [], array $columns = ['*']): Collection
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);
        $query = $this->applyRelations($query, $relations);
        $query = $this->applyColumns($query, $columns);
        $query = $this->applyChainedMethods($query);

        $result = $query->get();
        $this->resetState();

        return $result;
    }

    /**
     * @param int $id
     * @param array $relations
     * @param array $columns
     * @return Model|null
     */
    public function findById(int $id, array $relations = [], array $columns = ['*']): ?Model
    {
        $query = $this->newQuery();
        $query = $this->applyRelations($query, $relations);
        $query = $this->applyColumns($query, $columns);

        $result = $query->find($id);
        $this->resetState();

        return $result;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param array $relations
     * @return Model|null
     */
    public function findByField(string $field, mixed $value, array $relations = []): ?Model
    {
        $query = $this->newQuery();
        $query = $this->applyRelations($query, $relations);

        $result = $query->where($field, $value)->first();
        $this->resetState();

        return $result;
    }

    /**
     * @param string $field
     * @param mixed $value
     * @param array $relations
     * @return Collection
     */
    public function findAllByField(string $field, mixed $value, array $relations = []): Collection
    {
        $query = $this->newQuery();
        $query = $this->applyRelations($query, $relations);
        $query = $this->applyChainedMethods($query);

        $result = $query->where($field, $value)->get();
        $this->resetState();

        return $result;
    }

    /**
     * @param array $conditions
     * @param array $relations
     * @param array $columns
     * @return Model|null
     */
    public function findByConditions(array $conditions, array $relations = [], array $columns = ['*']): ?Model
    {
        $query = $this->newQuery();
        $query = $this->applyRelations($query, $relations);
        $query = $this->applyColumns($query, $columns);

        $result = $query->where($conditions)->first();
        $this->resetState();

        return $result;
    }

    /**
     * @param array $conditions
     * @param array $relations
     * @param array $columns
     * @return Collection
     */
    public function findAllByConditions(array $conditions, array $relations = [], array $columns = ['*']): Collection
    {
        $query = $this->newQuery();
        $query = $this->applyRelations($query, $relations);
        $query = $this->applyColumns($query, $columns);
        $query = $this->applyChainedMethods($query);

        $result = $query->where($conditions)->get();
        $this->resetState();

        return $result;
    }

    /**
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = 15, array $filters = [], array $relations = [], array $columns = ['*']): LengthAwarePaginator
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);
        $query = $this->applyRelations($query, $relations);
        $query = $this->applyColumns($query, $columns);
        $query = $this->applyChainedMethods($query);

        $result = $query->paginate($perPage);
        $this->resetState();

        return $result;
    }

    /**
     * @param int $perPage
     * @param array $filters
     * @param array $relations
     * @return Paginator
     */
    public function simplePaginate(int $perPage = 15, array $filters = [], array $relations = []): Paginator
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);
        $query = $this->applyRelations($query, $relations);
        $query = $this->applyChainedMethods($query);

        $result = $query->simplePaginate($perPage);
        $this->resetState();

        return $result;
    }

    /**
     * @param array $filters
     * @return int
     */
    public function count(array $filters = []): int
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);

        $result = $query->count();
        $this->resetState();

        return $result;
    }

    /**
     * @param array $conditions
     * @return bool
     */
    public function exists(array $conditions): bool
    {
        $query = $this->newQuery();

        $result = $query->where($conditions)->exists();
        $this->resetState();

        return $result;
    }

    /**
     * @param string $column
     * @param array $filters
     * @return mixed
     */
    public function max(string $column, array $filters = []): mixed
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);

        $result = $query->max($column);
        $this->resetState();

        return $result;
    }

    /**
     * @param string $column
     * @param array $filters
     * @return mixed
     */
    public function min(string $column, array $filters = []): mixed
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);

        $result = $query->min($column);
        $this->resetState();

        return $result;
    }

    /**
     * @param string $column
     * @param array $filters
     * @return int|float
     */
    public function sum(string $column, array $filters = []): int|float
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);

        $result = $query->sum($column);
        $this->resetState();

        return $result;
    }

    /**
     * @param string $column
     * @param string|null $keyColumn
     * @param array $filters
     * @return Collection
     */
    public function pluck(string $column, ?string $keyColumn = null, array $filters = []): Collection
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);

        $result = $query->pluck($column, $keyColumn);
        $this->resetState();

        return $result;
    }

    /**
     * @param array $data
     * @return Model
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    /**
     * @param array $records
     * @return Collection
     */
    public function createMany(array $records): Collection
    {
        $models = collect();

        foreach ($records as $record) {
            $models->push($this->model->create($record));
        }

        return $models;
    }

    /**
     * @param int $id
     * @param array $data
     * @return Model
     * @throws NotFoundException
     */
    public function update(int $id, array $data): Model
    {
        $model = $this->resolveModel($id);
        $model->update($data);

        return $model->fresh();
    }

    /**
     * @param array $conditions
     * @param array $data
     * @return bool
     */
    public function updateByConditions(array $conditions, array $data): bool
    {
        return $this->newQuery()->where($conditions)->update($data);
    }

    /**
     * @param array $conditions
     * @param array $data
     * @return Model
     */
    public function updateOrCreate(array $conditions, array $data): Model
    {
        return $this->model->updateOrCreate($conditions, $data);
    }

    /**
     * @param array $conditions
     * @param array $data
     * @return Model
     */
    public function firstOrCreate(array $conditions, array $data): Model
    {
        return $this->model->firstOrCreate($conditions, $data);
    }

    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function delete(int $id): bool
    {
        $model = $this->resolveModel($id);

        return $model->delete();
    }

    /**
     * @param array $conditions
     * @return bool
     */
    public function deleteByConditions(array $conditions): bool
    {
        return $this->newQuery()->where($conditions)->delete();
    }

    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function softDelete(int $id): bool
    {
        $model = $this->resolveModel($id);

        return $model->delete();
    }

    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function restore(int $id): bool
    {
        $model = $this->model->withTrashed()->find($id);

        if (!$model) {
            throw new NotFoundException('Không tìm thấy bản ghi');
        }

        return $model->restore();
    }

    /**
     * @param int $id
     * @return bool
     * @throws NotFoundException
     */
    public function forceDelete(int $id): bool
    {
        $model = $this->model->withTrashed()->find($id);

        if (!$model) {
            throw new NotFoundException('Không tìm thấy bản ghi');
        }

        return $model->forceDelete();
    }

    /**
     * @param array $relations
     * @return static
     */
    public function with(array $relations): static
    {
        $this->eagerLoads = array_merge($this->eagerLoads, $relations);

        return $this;
    }

    /**
     * @param array $relations
     * @return static
     */
    public function withCount(array $relations): static
    {
        $this->eagerCounts = array_merge($this->eagerCounts, $relations);

        return $this;
    }

    /**
     * @param string $relation
     * @return static
     */
    public function has(string $relation): static
    {
        $this->hasRelations[] = $relation;

        return $this;
    }

    /**
     * @param string $relation
     * @param Closure $callback
     * @return static
     */
    public function whereHas(string $relation, Closure $callback): static
    {
        $this->whereHasCallbacks[$relation] = $callback;

        return $this;
    }

    /**
     * @param int $id
     * @return Model|null
     */
    public function findWithLock(int $id): ?Model
    {
        $query = $this->newQuery();

        $result = $query->lockForUpdate()->find($id);
        $this->resetState();

        return $result;
    }

    /**
     * @param array $conditions
     * @return Model|null
     */
    public function findByConditionsWithLock(array $conditions): ?Model
    {
        $query = $this->newQuery();

        $result = $query->where($conditions)->lockForUpdate()->first();
        $this->resetState();

        return $result;
    }

    /**
     * @param string $column
     * @param string $direction
     * @return static
     */
    public function orderBy(string $column, string $direction = 'asc'): static
    {
        $this->orderColumn = $column;
        $this->orderDirection = $direction;

        return $this;
    }

    /**
     * @param string $column
     * @return static
     */
    public function latest(string $column = 'created_at'): static
    {
        $this->orderColumn = $column;
        $this->orderDirection = 'desc';

        return $this;
    }

    /**
     * @param string $column
     * @return static
     */
    public function oldest(string $column = 'created_at'): static
    {
        $this->orderColumn = $column;
        $this->orderDirection = 'asc';

        return $this;
    }

    /**
     * @param int $size
     * @param Closure $callback
     * @param array $filters
     * @return void
     */
    public function chunk(int $size, Closure $callback, array $filters = []): void
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);

        $query->chunk($size, $callback);
        $this->resetState();
    }

    /**
     * @param int $size
     * @param Closure $callback
     * @param array $filters
     * @return void
     */
    public function chunkById(int $size, Closure $callback, array $filters = []): void
    {
        $query = $this->newQuery();
        $query = $this->applyFilters($query, $filters);

        $query->chunkById($size, $callback);
        $this->resetState();
    }

    /**
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        return $query;
    }

    /**
     * @return Builder
     */
    protected function newQuery(): Builder
    {
        return $this->model->newQuery();
    }

    /**
     * @param Builder $query
     * @param array $relations
     * @return Builder
     */
    protected function applyRelations(Builder $query, array $relations): Builder
    {
        if (!empty($relations)) {
            $query->with($relations);
        }

        return $query;
    }

    /**
     * @param Builder $query
     * @param array $columns
     * @return Builder
     */
    protected function applyColumns(Builder $query, array $columns): Builder
    {
        return $query->select($columns);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    protected function applyChainedMethods(Builder $query): Builder
    {
        if (!empty($this->eagerLoads)) {
            $query->with($this->eagerLoads);
        }

        if (!empty($this->eagerCounts)) {
            $query->withCount($this->eagerCounts);
        }

        foreach ($this->hasRelations as $relation) {
            $query->has($relation);
        }

        foreach ($this->whereHasCallbacks as $relation => $callback) {
            $query->whereHas($relation, $callback);
        }

        if ($this->orderColumn) {
            $query->orderBy($this->orderColumn, $this->orderDirection);
        }

        return $query;
    }

    /**
     * @param int $id
     * @return Model
     * @throws NotFoundException
     */
    protected function resolveModel(int $id): Model
    {
        $model = $this->model->find($id);

        if (!$model) {
            throw new NotFoundException('Không tìm thấy bản ghi');
        }

        return $model;
    }

    /**
     * @return void
     */
    private function resetState(): void
    {
        $this->eagerLoads = [];
        $this->eagerCounts = [];
        $this->orderColumn = null;
        $this->orderDirection = 'asc';
        $this->hasRelations = [];
        $this->whereHasCallbacks = [];
    }
}
