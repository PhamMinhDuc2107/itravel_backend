<?php

namespace App\Infrastructure\Database\Repositories\QueryBuilder;

use Illuminate\Database\Eloquent\Builder;

class Search
{
    private string $searchTerm;
    private array $searchableColumns;

    public function __construct(string $searchTerm, array $searchableColumns)
    {
        $this->searchTerm = $searchTerm;
        $this->searchableColumns = $searchableColumns;
    }

    public function apply(Builder $query): Builder
    {
        if (empty($this->searchTerm) || empty($this->searchableColumns)) {
            return $query;
        }

        return $query->where(function (Builder $query) {
            foreach ($this->searchableColumns as $column) {
                $query->orWhere($column, 'LIKE', '%' . $this->searchTerm . '%');
            }
        });
    }
}
