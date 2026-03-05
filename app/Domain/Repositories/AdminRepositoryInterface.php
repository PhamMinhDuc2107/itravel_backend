<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\AdminEntity;

interface AdminRepositoryInterface
{
    public function findByEmail(string $email): ?AdminEntity;

    public function findActiveById(int $adminId): ?AdminEntity;

    public function updateLastLoginAt(int $adminId): void;
}
