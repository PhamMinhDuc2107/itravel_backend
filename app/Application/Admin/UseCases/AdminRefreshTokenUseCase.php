<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminAuthResultDTO;
use App\Application\Admin\DTOs\AdminRefreshTokenDTO;
use App\Domain\Exceptions\UnauthorizedException;
use App\Domain\Repositories\AdminRefreshTokenRepositoryInterface;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Infrastructure\Services\Contracts\JwtServiceInterface;

final class AdminRefreshTokenUseCase
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly AdminRefreshTokenRepositoryInterface $adminRefreshTokenRepository,
        private readonly JwtServiceInterface $jwtService,
    ) {}

    public function execute(AdminRefreshTokenDTO $dto): AdminAuthResultDTO
    {
        $admin = $this->adminRepository->findActiveById($dto->adminId);
        if ($admin === null) {
            throw new UnauthorizedException('Admin khong ton tai hoac da bi khoa');
        }

        $data = $dto->data;
        if ($data === []) {
            $data = [
                'admin_id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'status' => $admin->status->value,
            ];
        }

        if (!$this->adminRefreshTokenRepository->isValidToken($dto->adminId, $dto->refreshToken)) {
            throw new UnauthorizedException('Refresh token khong hop le hoac da het han');
        }

        $accessToken = $this->jwtService->generateAccessToken($data, [], $dto->context);

        $resultDto = new AdminAuthResultDTO(
            admin: $admin,
            accessToken: $accessToken,
            refreshToken: null,
            refreshExpiresAt: null,
            expiresIn: $this->jwtService->getAccessTokenTtl(),
        );

        return $resultDto;
    }
}
