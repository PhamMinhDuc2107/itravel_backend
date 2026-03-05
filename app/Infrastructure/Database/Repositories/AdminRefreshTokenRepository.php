<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Enums\StatusStateEnum;
use App\Domain\Repositories\AdminRefreshTokenRepositoryInterface;
use App\Infrastructure\Database\Models\AdminRefreshTokenModel;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

final class AdminRefreshTokenRepository implements AdminRefreshTokenRepositoryInterface
{
    public function createToken(
        int $adminId,
        string $hashedToken,
        ?string $userAgent,
        ?string $ipAddress,
        int $ttlSeconds,
    ): string {
        $refreshToken = AdminRefreshTokenModel::query()->create([
            'user_id' => $adminId,
            'token' => $hashedToken,
            'user_agent' => $userAgent,
            'ip_address' => $ipAddress,
            'expires_at' => Carbon::now()->addSeconds($ttlSeconds),
            'is_revoked' => StatusStateEnum::INACTIVE,
        ]);

        return (string) $refreshToken->expires_at;
    }

    public function isValidToken(int $adminId, string $plainToken): bool
    {
        $refreshTokens = $this->validTokenCandidatesQuery($adminId)
            ->select(['id', 'token'])
            ->get();

        foreach ($refreshTokens as $refreshToken) {
            if (Hash::check($plainToken, (string) $refreshToken->token)) {
                return true;
            }
        }

        return false;
    }

    public function revokeToken(int $adminId, string $plainToken): bool
    {
        $refreshTokens = $this->validTokenCandidatesQuery($adminId)
            ->select(['id', 'token'])
            ->get();

        foreach ($refreshTokens as $refreshToken) {
            if (Hash::check($plainToken, (string) $refreshToken->token)) {
                $refreshToken->update(['is_revoked' => StatusStateEnum::ACTIVE]);

                return true;
            }
        }

        return false;
    }

    public function revokeAllTokens(int $adminId): int
    {
        return $this->validTokenCandidatesQuery($adminId)
            ->update(['is_revoked' => StatusStateEnum::ACTIVE]);
    }

    public function cleanupExpiredTokens(): int
    {
        return AdminRefreshTokenModel::query()
            ->where('expires_at', '<', now())
            ->delete();
    }

    private function validTokenCandidatesQuery(int $adminId): Builder
    {
        return AdminRefreshTokenModel::query()
            ->where('user_id', $adminId)
            ->where('is_revoked', StatusStateEnum::INACTIVE)
            ->where('expires_at', '>', now())
            ->whereHas('admin', static function (Builder $query): void {
                $query->where('status', StatusStateEnum::ACTIVE->value);
            });
    }
}
