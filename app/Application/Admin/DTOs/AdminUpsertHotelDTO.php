<?php

declare(strict_types=1);

namespace App\Application\Admin\DTOs;

readonly final class AdminUpsertHotelDTO
{
    /**
     * @param array<int, array{url: string, alt: string|null, is_cover: bool, sort_order: int}>|null $hotelImages
     * @param array<int, int>|null $amenityIds
     */
    public function __construct(
        public int $locationId,
        public int $hotelTypeId,
        public string $name,
        public ?string $thumbnail,
        public ?int $starRating,
        public ?string $address,
        public ?string $ward,
        public ?string $district,
        public ?string $latitude,
        public ?string $longitude,
        public ?string $googleMapUrl,
        public ?string $description,
        public ?string $priceFrom,
        public bool $isFreeCancel,
        public bool $isPayLater,
        public bool $isFeatured,
        public bool $isActive,
        public ?string $ratingScore,
        public int $ratingCount,
        public ?string $metaTitle,
        public ?string $metaDescription,
        public ?int $actorId,
        public ?array $hotelImages = null,
        public ?array $amenityIds = null,
    ) {}

    /** @return array<string, mixed> */
    public function toPayload(bool $isUpdate = false): array
    {
        $payload = [
            'location_id' => $this->locationId,
            'hotel_type_id' => $this->hotelTypeId,
            'name' => $this->name,
            'thumbnail' => $this->thumbnail,
            'star_rating' => $this->starRating,
            'address' => $this->address,
            'ward' => $this->ward,
            'district' => $this->district,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'google_map_url' => $this->googleMapUrl,
            'description' => $this->description,
            'price_from' => $this->priceFrom,
            'is_free_cancel' => $this->isFreeCancel,
            'is_pay_later' => $this->isPayLater,
            'is_featured' => $this->isFeatured,
            'is_active' => $this->isActive,
            'rating_score' => $this->ratingScore,
            'rating_count' => $this->ratingCount,
            'meta_title' => $this->metaTitle,
            'meta_description' => $this->metaDescription,
        ];

        if ($isUpdate) {
            $payload['updated_by'] = $this->actorId;

            if ($this->hotelImages !== null) {
                $payload['hotel_images'] = $this->hotelImages;
            }

            if ($this->amenityIds !== null) {
                $payload['amenity_ids'] = $this->amenityIds;
            }

            return $payload;
        }

        $payload['created_by'] = $this->actorId;

        if ($this->hotelImages !== null) {
            $payload['hotel_images'] = $this->hotelImages;
        }

        if ($this->amenityIds !== null) {
            $payload['amenity_ids'] = $this->amenityIds;
        }

        return $payload;
    }
}
