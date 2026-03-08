<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\ServicePageRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminServicePageUseCase
{
    public function __construct(private readonly ServicePageRepositoryInterface $servicePageRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->servicePageRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('Service page khong ton tai');
            }
        });
    }
}
