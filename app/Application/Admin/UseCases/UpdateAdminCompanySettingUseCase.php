<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertCompanySettingDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\CompanySettingRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminCompanySettingUseCase
{
    public function __construct(private readonly CompanySettingRepositoryInterface $companySettingRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertCompanySettingDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->companySettingRepository->updateAndLoadById($id, $dto->toPayload());
            if ($item === null) {
                throw new NotFoundException('Company setting khong ton tai');
            }

            return $item;
        });
    }
}
