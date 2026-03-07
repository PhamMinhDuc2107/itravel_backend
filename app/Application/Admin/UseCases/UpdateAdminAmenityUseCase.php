<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertAmenityDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\AmenityRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminAmenityUseCase
{
    public function __construct(private readonly AmenityRepositoryInterface $amenityRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertAmenityDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->amenityRepository->updateAndLoadById($id, $dto->toPayload());
            if ($item === null) {
                throw new NotFoundException('Amenity khong ton tai');
            }

            return $item;
        });
    }
}
