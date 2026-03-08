<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertTourDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\TourImageRepositoryInterface;
use App\Domain\Repositories\TourItineraryRepositoryInterface;
use App\Domain\Repositories\TourLocationRepositoryInterface;
use App\Domain\Repositories\TourNoteRepositoryInterface;
use App\Domain\Repositories\TourPriceOverrideRepositoryInterface;
use App\Domain\Repositories\TourPriceRepositoryInterface;
use App\Domain\Repositories\TourRepositoryInterface;
use App\Domain\Repositories\TourScheduleRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminTourUseCase
{
    public function __construct(
        private readonly TourRepositoryInterface $tourRepository,
        private readonly TourImageRepositoryInterface $tourImageRepository,
        private readonly TourPriceOverrideRepositoryInterface $tourPriceOverrideRepository,
        private readonly TourItineraryRepositoryInterface $tourItineraryRepository,
        private readonly TourNoteRepositoryInterface $tourNoteRepository,
        private readonly TourPriceRepositoryInterface $tourPriceRepository,
        private readonly TourScheduleRepositoryInterface $tourScheduleRepository,
        private readonly TourLocationRepositoryInterface $tourLocationRepository,
    ) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertTourDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $payload = $dto->toPayload(isUpdate: true);

            $hasTourImages = array_key_exists('tour_images', $payload);
            $tourImages = $hasTourImages ? (array) ($payload['tour_images'] ?? []) : [];

            $hasTourPriceOverrides = array_key_exists('tour_price_overrides', $payload);
            $tourPriceOverrides = $hasTourPriceOverrides ? (array) ($payload['tour_price_overrides'] ?? []) : [];

            $hasTourItineraries = array_key_exists('tour_itineraries', $payload);
            $tourItineraries = $hasTourItineraries ? (array) ($payload['tour_itineraries'] ?? []) : [];

            $hasTourNotes = array_key_exists('tour_notes', $payload);
            $tourNotes = $hasTourNotes ? (array) ($payload['tour_notes'] ?? []) : [];

            $hasTourPrices = array_key_exists('tour_prices', $payload);
            $tourPrices = $hasTourPrices ? (array) ($payload['tour_prices'] ?? []) : [];

            $hasTourSchedules = array_key_exists('tour_schedules', $payload);
            $tourSchedules = $hasTourSchedules ? (array) ($payload['tour_schedules'] ?? []) : [];

            $hasTourLocations = array_key_exists('tour_locations', $payload);
            $tourLocations = $hasTourLocations ? (array) ($payload['tour_locations'] ?? []) : [];

            unset(
                $payload['tour_images'],
                $payload['tour_price_overrides'],
                $payload['tour_itineraries'],
                $payload['tour_notes'],
                $payload['tour_prices'],
                $payload['tour_schedules'],
                $payload['tour_locations'],
            );

            $base = $this->tourRepository->updateAndLoadById($id, $payload);
            if ($base === null) {
                throw new NotFoundException('Tour khong ton tai');
            }

            if ($hasTourImages) {
                $this->tourImageRepository->syncByTourId($id, $tourImages);
            }

            if ($hasTourPriceOverrides) {
                $this->tourPriceOverrideRepository->syncByTourId($id, $tourPriceOverrides);
            }

            if ($hasTourItineraries) {
                $this->tourItineraryRepository->syncByTourId($id, $tourItineraries);
            }

            if ($hasTourNotes) {
                $this->tourNoteRepository->syncByTourId($id, $tourNotes);
            }

            if ($hasTourPrices) {
                $this->tourPriceRepository->syncByTourId($id, $tourPrices);
            }

            if ($hasTourSchedules) {
                $this->tourScheduleRepository->syncByTourId($id, $tourSchedules);
            }

            if ($hasTourLocations) {
                $this->tourLocationRepository->syncByTourId($id, $tourLocations);
            }

            return $this->tourRepository->findDetailById($id) ?? $base;
        });
    }
}
