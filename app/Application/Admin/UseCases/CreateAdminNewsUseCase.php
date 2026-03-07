<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertNewsDTO;
use App\Domain\Repositories\NewsRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminNewsUseCase
{
    public function __construct(private readonly NewsRepositoryInterface $newsRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertNewsDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->newsRepository->createAndLoad($dto->toPayload()));
    }
}
