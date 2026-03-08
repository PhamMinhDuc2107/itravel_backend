<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminLocationListDTO;
use App\Domain\Repositories\LocationRepositoryInterface;

final class ListAdminLocationsUseCase
{
    public function __construct(private readonly LocationRepositoryInterface $locationRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminLocationListDTO $dto): array
    {
        return $this->locationRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            parentId: $dto->parentId,
            isActive: $dto->isActive,
            isFeatured: $dto->isFeatured,
            isDomestic: $dto->isDomestic,
            type: $dto->type,
        );
    }
}
