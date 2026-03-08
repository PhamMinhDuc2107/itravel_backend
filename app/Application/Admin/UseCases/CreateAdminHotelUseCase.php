<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertHotelDTO;
use App\Domain\Repositories\HotelAmenityRepositoryInterface;
use App\Domain\Repositories\HotelImageRepositoryInterface;
use App\Domain\Repositories\HotelRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminHotelUseCase
{
    public function __construct(
        private readonly HotelRepositoryInterface $hotelRepository,
        private readonly HotelImageRepositoryInterface $hotelImageRepository,
        private readonly HotelAmenityRepositoryInterface $hotelAmenityRepository,
    ) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertHotelDTO $dto): array
    {
        return DB::transaction(function () use ($dto): array {
            $payload = $dto->toPayload();

            $hasHotelImages = array_key_exists('hotel_images', $payload);
            $hotelImages = $hasHotelImages ? (array) ($payload['hotel_images'] ?? []) : [];

            $hasAmenityIds = array_key_exists('amenity_ids', $payload);
            $amenityIds = $hasAmenityIds ? (array) ($payload['amenity_ids'] ?? []) : [];

            unset($payload['hotel_images'], $payload['amenity_ids']);

            $hotel = $this->hotelRepository->createAndLoad($payload);
            $hotelId = (int) ($hotel['id'] ?? 0);

            if ($hotelId <= 0) {
                return $hotel;
            }

            if ($hasHotelImages) {
                $this->hotelImageRepository->syncByHotelId($hotelId, $hotelImages);
            }

            if ($hasAmenityIds) {
                $this->hotelAmenityRepository->syncByHotelId($hotelId, $amenityIds);
            }

            return $this->hotelRepository->findDetailById($hotelId) ?? $hotel;
        });
    }
}
