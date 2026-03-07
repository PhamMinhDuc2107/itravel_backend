<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\CompanySettingRepositoryInterface;

final class GetAdminCompanySettingDetailUseCase
{
    public function __construct(private readonly CompanySettingRepositoryInterface $companySettingRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->companySettingRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('Company setting khong ton tai');
        }

        return $item;
    }
}
