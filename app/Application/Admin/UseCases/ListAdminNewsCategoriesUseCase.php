<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminNewsCategoryListDTO;
use App\Domain\Repositories\NewsCategoryRepositoryInterface;

final class ListAdminNewsCategoriesUseCase
{
    public function __construct(private readonly NewsCategoryRepositoryInterface $newsCategoryRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminNewsCategoryListDTO $dto): array
    {
        return $this->newsCategoryRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            parentId: $dto->parentId,
            isActive: $dto->isActive,
        );
    }
}
