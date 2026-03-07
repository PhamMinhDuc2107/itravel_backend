<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminTourListDTO;
use App\Domain\Repositories\TourRepositoryInterface;

final class ListAdminToursUseCase
{
    public function __construct(private readonly TourRepositoryInterface $tourRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminTourListDTO $dto): array
    {
        return $this->tourRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            categoryId: $dto->categoryId,
            status: $dto->status,
            isFeatured: $dto->isFeatured,
            isHot: $dto->isHot,
        );
    }
}
