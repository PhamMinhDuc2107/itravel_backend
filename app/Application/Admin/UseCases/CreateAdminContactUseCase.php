<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertContactDTO;
use App\Domain\Repositories\ContactRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminContactUseCase
{
    public function __construct(private readonly ContactRepositoryInterface $contactRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertContactDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->contactRepository->createAndLoad($dto->toPayload()));
    }
}
