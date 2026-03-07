<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminHotelTypeListDTO;
use App\Domain\Repositories\HotelTypeRepositoryInterface;

final class ListAdminHotelTypesUseCase
{
    public function __construct(private readonly HotelTypeRepositoryInterface $hotelTypeRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminHotelTypeListDTO $dto): array
    {
        return $this->hotelTypeRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            isActive: $dto->isActive,
        );
    }
}
