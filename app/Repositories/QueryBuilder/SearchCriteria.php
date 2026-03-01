<?php

namespace App\Repositories\QueryBuilder;

class SearchCriteria
{
    public function __construct(
        public array $filters = [],
        public readonly array $sortableColumns = [],
        public readonly array $sortConditions = [],
        public readonly ?array $pagination = null,
        public readonly ?string $searchTerm = null,
        public readonly ?array $searchableColumns = null,
    ) {}
}
