<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminServicePageListDTO;
use App\Domain\Repositories\ServicePageRepositoryInterface;

final class ListAdminServicePagesUseCase
{
    public function __construct(private readonly ServicePageRepositoryInterface $servicePageRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminServicePageListDTO $dto): array
    {
        return $this->servicePageRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            categoryId: $dto->categoryId,
            isActive: $dto->isActive,
            isFeatured: $dto->isFeatured,
        );
    }
}
