<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertTourDTO;
use App\Domain\Repositories\TourRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminTourUseCase
{
    public function __construct(private readonly TourRepositoryInterface $tourRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertTourDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->tourRepository->createAndLoad($dto->toPayload()));
    }
}
