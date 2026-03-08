<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertHotelDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\HotelAmenityRepositoryInterface;
use App\Domain\Repositories\HotelImageRepositoryInterface;
use App\Domain\Repositories\HotelRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminHotelUseCase
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository,
        private readonly HotelImageRepositoryInterface $hotelImageRepository,
        private readonly HotelAmenityRepositoryInterface $hotelAmenityRepository,
    ) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertHotelDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $payload = $dto->toPayload(isUpdate: true);

            $hasHotelImages = array_key_exists('hotel_images', $payload);
            $hotelImages = $hasHotelImages ? (array) ($payload['hotel_images'] ?? []) : [];

            $hasAmenityIds = array_key_exists('amenity_ids', $payload);
            $amenityIds = $hasAmenityIds ? (array) ($payload['amenity_ids'] ?? []) : [];

            unset($payload['hotel_images'], $payload['amenity_ids']);

            $base = $this->hotelRepository->updateAndLoadById($id, $payload);
            if ($base === null) {
                throw new NotFoundException('Hotel khong ton tai');
            }

            if ($hasHotelImages) {
                $this->hotelImageRepository->syncByHotelId($id, $hotelImages);
            }

            if ($hasAmenityIds) {
                $this->hotelAmenityRepository->syncByHotelId($id, $amenityIds);
            }

            return $this->hotelRepository->findDetailById($id) ?? $base;
        });
    }
}
