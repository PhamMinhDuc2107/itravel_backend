<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminLogoutDTO;
use App\Domain\Repositories\AdminRefreshTokenRepositoryInterface;

final class AdminLogoutUseCase
{
    public function __construct(private readonly AdminRefreshTokenRepositoryInterface $adminRefreshTokenRepository) {}

    public function execute(AdminLogoutDTO $dto): void
    {
        if ($dto->refreshToken !== null && $dto->refreshToken !== '') {
            $this->adminRefreshTokenRepository->revokeToken($dto->adminId, $dto->refreshToken);

            return;
        }

        $this->adminRefreshTokenRepository->revokeAllTokens($dto->adminId);
    }
}
