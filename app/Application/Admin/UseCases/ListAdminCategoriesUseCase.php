<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminCategoryListDTO;
use App\Domain\Repositories\CategoryRepositoryInterface;

final class ListAdminCategoriesUseCase
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminCategoryListDTO $dto): array
    {
        return $this->categoryRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            parentId: $dto->parentId,
            isActive: $dto->isActive,
            isFeatured: $dto->isFeatured,
            type: $dto->type,
        );
    }
}
