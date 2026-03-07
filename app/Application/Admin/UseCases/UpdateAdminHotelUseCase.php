<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertHotelDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\HotelRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminHotelUseCase
{
    public function __construct(private readonly HotelRepositoryInterface $hotelRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertHotelDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->hotelRepository->updateAndLoadById($id, $dto->toPayload(isUpdate: true));
            if ($item === null) {
                throw new NotFoundException('Hotel khong ton tai');
            }

            return $item;
        });
    }
}
