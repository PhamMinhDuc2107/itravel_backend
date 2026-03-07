<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminHotelListDTO;
use App\Domain\Repositories\HotelRepositoryInterface;

final class ListAdminHotelsUseCase
{
    public function __construct(private readonly HotelRepositoryInterface $hotelRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminHotelListDTO $dto): array
    {
        return $this->hotelRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            locationId: $dto->locationId,
            hotelTypeId: $dto->hotelTypeId,
            isActive: $dto->isActive,
            isFeatured: $dto->isFeatured,
        );
    }
}
