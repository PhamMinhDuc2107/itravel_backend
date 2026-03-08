<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\NewsRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminNewsUseCase
{
    public function __construct(private readonly NewsRepositoryInterface $newsRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->newsRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('News khong ton tai');
            }
        });
    }
}
