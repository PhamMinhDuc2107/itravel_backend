<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminNewsListDTO;
use App\Domain\Repositories\NewsRepositoryInterface;

final class ListAdminNewsUseCase
{
    public function __construct(private readonly NewsRepositoryInterface $newsRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminNewsListDTO $dto): array
    {
        return $this->newsRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
            newsCategoryId: $dto->newsCategoryId,
            status: $dto->status,
            isFeatured: $dto->isFeatured,
        );
    }
}
