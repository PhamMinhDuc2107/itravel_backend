<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertAmenityDTO;
use App\Domain\Repositories\AmenityRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminAmenityUseCase
{
    public function __construct(private readonly AmenityRepositoryInterface $amenityRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertAmenityDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->amenityRepository->createAndLoad($dto->toPayload()));
    }
}
