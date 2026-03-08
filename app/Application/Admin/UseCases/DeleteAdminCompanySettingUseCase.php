<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\CompanySettingRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminCompanySettingUseCase
{
    public function __construct(private readonly CompanySettingRepositoryInterface $companySettingRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->companySettingRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('Company setting khong ton tai');
            }
        });
    }
}
