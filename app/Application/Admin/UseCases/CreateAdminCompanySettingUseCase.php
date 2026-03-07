<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertCompanySettingDTO;
use App\Domain\Repositories\CompanySettingRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminCompanySettingUseCase
{
    public function __construct(private readonly CompanySettingRepositoryInterface $companySettingRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertCompanySettingDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->companySettingRepository->createAndLoad($dto->toPayload()));
    }
}
