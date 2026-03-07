<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

interface TourRepositoryInterface extends RepositoryInterface
{
    /**
     * @return array{items: array<int, array<string, mixed>>, pagination: array<string, int|null>}
     */
    public function paginateForAdmin(
        int $page,
        int $perPage,
        ?string $search,
        ?string $searchBy,
        ?int $categoryId,
        ?string $status,
        ?bool $isFeatured,
        ?bool $isHot,
    ): array;

    /** @return array<string, mixed>|null */
    public function findDetailById(int $id): ?array;

    /** @param array<string, mixed> $payload
     *  @return array<string, mixed>
     */
    public function createAndLoad(array $payload): array;

    /** @param array<string, mixed> $payload
     *  @return array<string, mixed>|null
     */
    public function updateAndLoadById(int $id, array $payload): ?array;

    public function deleteExistingById(int $id): bool;
}
