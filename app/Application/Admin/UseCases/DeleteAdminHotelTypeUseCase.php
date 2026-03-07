<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\HotelTypeRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class DeleteAdminHotelTypeUseCase
{
    public function __construct(private readonly HotelTypeRepositoryInterface $hotelTypeRepository) {}

    public function execute(int $id): void
    {
        DB::transaction(function () use ($id): void {
            $deleted = $this->hotelTypeRepository->deleteExistingById($id);
            if (!$deleted) {
                throw new NotFoundException('Hotel type khong ton tai');
            }
        });
    }
}
