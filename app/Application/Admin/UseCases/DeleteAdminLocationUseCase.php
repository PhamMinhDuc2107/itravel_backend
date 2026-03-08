<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\LocationRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminLocationUseCase
{
    public function __construct(private readonly LocationRepositoryInterface $locationRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->locationRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('Location khong ton tai');
            }
        });
    }
}
