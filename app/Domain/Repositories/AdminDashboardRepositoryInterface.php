<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface AdminDashboardRepositoryInterface
{
    /** @return array<string, int> */
    public function getOverviewStats(): array;

    /** @return array<int, array<string, mixed>> */
    public function getTopTours(int $limit): array;

    /** @return array<int, array<string, mixed>> */
    public function getLatestContacts(int $limit): array;

    /** @return array<int, array<string, mixed>> */
    public function getTopCategories(int $limit): array;
}
