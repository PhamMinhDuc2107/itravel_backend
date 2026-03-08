<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertCategoryDTO;
use App\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminCategoryUseCase
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertCategoryDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->categoryRepository->createAndLoad($dto->toPayload()));
    }
}
