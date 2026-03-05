<?php

namespace App\Infrastructure\Database\Repositories\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;

class Sort
{
    private array $sortableColumns;
    private array $sortConditions;

    public function __construct(array $sortableColumns = [], array $sortConditions = [])
    {
        $this->sortableColumns = $sortableColumns;
        $this->sortConditions = $sortConditions;
    }

    public function apply(Builder $query, ?array $sortableColumns = null, ?array $sortConditions = null): Builder
    {
        if ($sortableColumns !== null) {
            $this->sortableColumns = $sortableColumns;
        }

        if ($sortConditions !== null) {
            $this->sortConditions = $sortConditions;
        }

        foreach ($this->sortConditions as $condition) {
            if (in_array($condition['column'], $this->sortableColumns, true)) {
                $query->orderBy($condition['column'], $condition['direction']);
            }
        }

        return $query;
    }
}
