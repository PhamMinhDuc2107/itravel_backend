<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminCategoryUseCase
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->categoryRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('Category khong ton tai');
            }
        });
    }
}
