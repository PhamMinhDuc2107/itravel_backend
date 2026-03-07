<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminAmenityListDTO;
use App\Domain\Repositories\AmenityRepositoryInterface;

final class ListAdminAmenitiesUseCase
{
    public function __construct(private readonly AmenityRepositoryInterface $amenityRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminAmenityListDTO $dto): array
    {
        return $this->amenityRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            type: $dto->type,
            isActive: $dto->isActive,
        );
    }
}
