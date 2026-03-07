<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminContactListDTO;
use App\Domain\Repositories\ContactRepositoryInterface;

final class ListAdminContactsUseCase
{
    public function __construct(private readonly ContactRepositoryInterface $contactRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminContactListDTO $dto): array
    {
        return $this->contactRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            status: $dto->status,
            resolvedBy: $dto->resolvedBy,
        );
    }
}
