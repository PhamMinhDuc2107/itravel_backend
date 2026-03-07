<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertTourDTO
{
    /**
     * @param array<int, array{url: string, alt: string|null, is_cover: bool, sort: int}>|null $tourImages
     * @param array<int, array{tour_schedule_id: int, departure_date: string, adult_price: int|null, child_price: int|null, infant_price: int|null, is_active: bool, note: string|null}>|null $tourPriceOverrides
        * @param array<int, array{day_number: int, title: string, content: string|null}>|null $tourItineraries
        * @param array<int, array{title: string, content: string|null, sort: int}>|null $tourNotes
        * @param array<int, array{passenger_type: int, price: int, currency: string, includes: string|null, excludes: string|null}>|null $tourPrices
        * @param array<int, array{departure_date: string, return_date: string|null, max_slots: int, booked_slots: int, status: int, note: string|null}>|null $tourSchedules
        * @param array<int, array{location_id: int, role: int, sort: int}>|null $tourLocations
     */
    public function __construct(
        public int $categoryId,
        public ?string $code,
        public string $title,
        public ?string $thumbnail,
        public ?string $description,
        public ?int $durationDays,
        public ?int $durationNights,
        public ?string $departureFrom,
        public ?string $destination,
        public ?string $attractions,
        public ?string $cuisine,
        public ?string $suitableFor,
        public ?string $status,
        public bool $isFeatured,
        public bool $isHot,
        public int $viewCount,
        public ?string $metaTitle,
        public ?string $metaDescription,
        public ?int $actorId,
        public ?array $tourImages = null,
        public ?array $tourPriceOverrides = null,
        public ?array $tourItineraries = null,
        public ?array $tourNotes = null,
        public ?array $tourPrices = null,
        public ?array $tourSchedules = null,
        public ?array $tourLocations = null,
    ) {}

    /** @return array<string, mixed> */
    public function toPayload(bool $isUpdate = false): array
    {
        $payload = [
            'category_id' => $this->categoryId,
            'code' => $this->code,
            'title' => $this->title,
            'thumbnail' => $this->thumbnail,
            'description' => $this->description,
            'duration_days' => $this->durationDays,
            'duration_nights' => $this->durationNights,
            'departure_from' => $this->departureFrom,
            'destination' => $this->destination,
            'attractions' => $this->attractions,
            'cuisine' => $this->cuisine,
            'suitable_for' => $this->suitableFor,
            'status' => $this->status,
            'is_featured' => $this->isFeatured,
            'is_hot' => $this->isHot,
            'view_count' => $this->viewCount,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
        ];

        if ($isUpdate) {
            $payload['updated_by'] = $this->actorId;

            if ($this->tourImages !== null) {
                $payload['tour_images'] = $this->tourImages;
            }

            if ($this->tourPriceOverrides !== null) {
                $payload['tour_price_overrides'] = $this->tourPriceOverrides;
            }

            if ($this->tourItineraries !== null) {
                $payload['tour_itineraries'] = $this->tourItineraries;
            }

            if ($this->tourNotes !== null) {
                $payload['tour_notes'] = $this->tourNotes;
            }

            if ($this->tourPrices !== null) {
                $payload['tour_prices'] = $this->tourPrices;
            }

            if ($this->tourSchedules !== null) {
                $payload['tour_schedules'] = $this->tourSchedules;
            }

            if ($this->tourLocations !== null) {
                $payload['tour_locations'] = $this->tourLocations;
            }

            return $payload;
        }

        $payload['created_by'] = $this->actorId;

        if ($this->tourImages !== null) {
            $payload['tour_images'] = $this->tourImages;
        }

        if ($this->tourPriceOverrides !== null) {
            $payload['tour_price_overrides'] = $this->tourPriceOverrides;
        }

        if ($this->tourItineraries !== null) {
            $payload['tour_itineraries'] = $this->tourItineraries;
        }

        if ($this->tourNotes !== null) {
            $payload['tour_notes'] = $this->tourNotes;
        }

        if ($this->tourPrices !== null) {
            $payload['tour_prices'] = $this->tourPrices;
        }

        if ($this->tourSchedules !== null) {
            $payload['tour_schedules'] = $this->tourSchedules;
        }

        if ($this->tourLocations !== null) {
            $payload['tour_locations'] = $this->tourLocations;
        }

        return $payload;
    }
}
