<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Entities\AdminEntity;
use App\Domain\Exceptions\UnauthorizedException;
use App\Domain\Repositories\AdminRepositoryInterface;

final class GetAdminProfileUseCase
{
    public function __construct(private readonly AdminRepositoryInterface $adminRepository) {}

    public function execute(int $adminId): AdminEntity
    {
        $admin = $this->adminRepository->findActiveById($adminId);
        if ($admin === null) {
            throw new UnauthorizedException('Admin khong ton tai hoac da bi khoa');
        }

        return $admin;
    }
}
