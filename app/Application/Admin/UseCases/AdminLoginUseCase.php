<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminAuthResultDTO;
use App\Application\Admin\DTOs\AdminLoginDTO;
use App\Domain\Exceptions\UnauthorizedException;
use App\Domain\Repositories\AdminRefreshTokenRepositoryInterface;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Infrastructure\Services\Contracts\JwtServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

final class AdminLoginUseCase
{
    public function __construct(
        private readonly AdminRepositoryInterface $adminRepository,
        private readonly AdminRefreshTokenRepositoryInterface $adminRefreshTokenRepository,
        private readonly JwtServiceInterface $jwtService,
    ) {}

    public function execute(AdminLoginDTO $dto): AdminAuthResultDTO
    {
        $admin = $this->adminRepository->findByEmail($dto->email);

        if (
            $admin === null
            || !$admin->isActive()
            || !Hash::check($dto->password, $admin->password)
        ) {
            throw new UnauthorizedException('Thong tin dang nhap khong hop le');
        }

        return DB::transaction(function () use ($admin, $dto): AdminAuthResultDTO {
            $this->adminRepository->updateLastLoginAt($admin->id);

            $accessToken = $this->jwtService->generateAccessToken([
                'admin_id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'status' => $admin->status->value,
            ], [], $dto->context);

            $rawRefreshToken = Str::random(64);
            $refreshExpiresAt = $this->adminRefreshTokenRepository->createToken(
                adminId: $admin->id,
                hashedToken: Hash::make($rawRefreshToken),
                userAgent: $dto->userAgent,
                ipAddress: $dto->ipAddress,
                ttlSeconds: (int) config('jwt.refresh_token_ttl', 604800),
            );

            $resultDto = new AdminAuthResultDTO(
                admin: $admin,
                accessToken: $accessToken,
                refreshToken: $rawRefreshToken,
                refreshExpiresAt: $refreshExpiresAt,
                expiresIn: null,
            );

            return $resultDto;
        });
    }
}
