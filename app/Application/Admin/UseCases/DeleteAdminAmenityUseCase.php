<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\AmenityRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminAmenityUseCase
{
    public function __construct(private readonly AmenityRepositoryInterface $amenityRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->amenityRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('Amenity khong ton tai');
            }
        });
    }
}
