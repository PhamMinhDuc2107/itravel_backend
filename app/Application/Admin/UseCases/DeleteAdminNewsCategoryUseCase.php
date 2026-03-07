<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\NewsCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminNewsCategoryUseCase
{
    public function __construct(private readonly NewsCategoryRepositoryInterface $newsCategoryRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->newsCategoryRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('News category khong ton tai');
            }
        });
    }
}
