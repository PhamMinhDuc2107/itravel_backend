<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertLocationDTO;
use App\Domain\Repositories\LocationRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminLocationUseCase
{
    public function __construct(private readonly LocationRepositoryInterface $locationRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertLocationDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->locationRepository->createAndLoad($dto->toPayload()));
    }
}
