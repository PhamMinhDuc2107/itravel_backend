<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\TourRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminTourUseCase
{
    public function __construct(private readonly TourRepositoryInterface $tourRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->tourRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('Tour khong ton tai');
            }
        });
    }
}
