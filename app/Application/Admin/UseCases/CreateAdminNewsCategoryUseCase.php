<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertNewsCategoryDTO;
use App\Domain\Repositories\NewsCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminNewsCategoryUseCase
{
    public function __construct(private readonly NewsCategoryRepositoryInterface $newsCategoryRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertNewsCategoryDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->newsCategoryRepository->createAndLoad($dto->toPayload()));
    }
}
