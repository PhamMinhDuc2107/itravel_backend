<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\ContactRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminContactUseCase
{
    public function __construct(private readonly ContactRepositoryInterface $contactRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->contactRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('Contact khong ton tai');
            }
        });
    }
}
