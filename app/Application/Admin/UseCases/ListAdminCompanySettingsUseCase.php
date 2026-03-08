<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminCompanySettingListDTO;
use App\Domain\Repositories\CompanySettingRepositoryInterface;

final class ListAdminCompanySettingsUseCase
{
    public function __construct(private readonly CompanySettingRepositoryInterface $companySettingRepository) {}

    /** @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>} */
    public function execute(AdminCompanySettingListDTO $dto): array
    {
        return $this->companySettingRepository->paginateForAdmin(
            page: $dto->page,
            perPage: $dto->perPage,
            search: $dto->search,
            searchBy: $dto->searchBy,
        );
    }
}
