<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertServicePageDTO;
use App\Domain\Repositories\ServicePageRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminServicePageUseCase
{
    public function __construct(private readonly ServicePageRepositoryInterface $servicePageRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertServicePageDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->servicePageRepository->createAndLoad($dto->toPayload()));
    }
}
