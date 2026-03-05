<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Entities\AdminEntity;
use App\Domain\Enums\StatusStateEnum;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Infrastructure\Database\Models\AdminModel;
use Illuminate\Support\Carbon;

class AdminRepository extends BaseRepository implements AdminRepositoryInterface
{
    private const DEFAULT_ADMIN_COLUMNS = [
        'id',
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
        'last_login_at',
        'created_at',
    ];

    public function __construct(AdminModel $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email): ?AdminEntity
    {
        $adminModel = AdminModel::query()
            ->select(self::DEFAULT_ADMIN_COLUMNS)
            ->where('email', $email)
            ->first();

        return $this->toEntity($adminModel);
    }

    public function findActiveById(int $adminId): ?AdminEntity
    {
        $adminModel = AdminModel::query()
            ->select(self::DEFAULT_ADMIN_COLUMNS)
            ->where('id', $adminId)
            ->where('status', StatusStateEnum::ACTIVE->value)
            ->first();

        return $this->toEntity($adminModel);
    }

    public function updateLastLoginAt(int $adminId): void
    {
        AdminModel::query()
            ->where('id', $adminId)
            ->update(['last_login_at' => now()]);
    }

    private function toEntity(?AdminModel $model): ?AdminEntity
    {
        if ($model === null) {
            return null;
        }

        return new AdminEntity(
            id: (int) $model->id,
            name: (string) $model->name,
            email: (string) $model->email,
            password: (string) $model->password,
            phone: $model->phone !== null ? (string) $model->phone : null,
            avatar: $model->avatar !== null ? (string) $model->avatar : null,
            status: StatusStateEnum::from((int) $model->status),
            last_login_at: $this->formatDateTime($model->last_login_at),
            created_at: $this->formatDateTime($model->created_at),
        );
    }

    private function formatDateTime(Carbon|string|null $dateTime): ?string
    {
        if ($dateTime instanceof Carbon) {
            return $dateTime->toISOString();
        }

        return $dateTime !== null ? (string) $dateTime : null;
    }
}
