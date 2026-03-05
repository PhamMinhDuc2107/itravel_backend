<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\AdminRefreshTokenRepositoryInterface;
use App\Infrastructure\Database\Models\AdminRefreshTokenModel;
use App\Domain\Enums\StatusStateEnum;
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
            'is_revoked' => false,
        ]);

        return (string) $refreshToken->expires_at;
    }

    public function isValidToken(int $adminId, string $plainToken): bool
    {
        return $this->findMatchingToken($adminId, $plainToken) !== null;
    }

    public function revokeToken(int $adminId, string $plainToken): bool
    {
        $refreshToken = $this->findMatchingToken($adminId, $plainToken);
        if ($refreshToken !== null) {
            $refreshToken->update(['is_revoked' => true]);

            return true;
        }

        return false;
    }

    public function revokeAllTokens(int $adminId): int
    {
        return $this->validTokenCandidatesQuery($adminId)
            ->update(['is_revoked' => true]);
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
            ->where('is_revoked', false)
            ->where('expires_at', '>', now())
            ->whereHas('admin', static function (Builder $query): void {
                $query->where('status', StatusStateEnum::ACTIVE->value);
            });
    }

    private function findMatchingToken(int $adminId, string $plainToken): ?AdminRefreshTokenModel
    {
        $refreshTokens = $this->validTokenCandidatesQuery($adminId)
            ->select(['id', 'token'])
            ->get();

        foreach ($refreshTokens as $refreshToken) {
            if (Hash::check($plainToken, (string) $refreshToken->token)) {
                return $refreshToken;
            }
        }

        return null;
    }
}
