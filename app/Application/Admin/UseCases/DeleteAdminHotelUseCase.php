<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\HotelRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminHotelUseCase
{
    public function __construct(private readonly HotelRepositoryInterface $hotelRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->hotelRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('Hotel khong ton tai');
            }
        });
    }
}
