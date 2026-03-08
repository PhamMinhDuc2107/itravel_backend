<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertHotelTypeDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\HotelTypeRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminHotelTypeUseCase
{
    public function __construct(private readonly HotelTypeRepositoryInterface $hotelTypeRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertHotelTypeDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->hotelTypeRepository->updateAndLoadById($id, $dto->toPayload());
            if ($item === null) {
                throw new NotFoundException('Hotel type khong ton tai');
            }

            return $item;
        });
    }
}
