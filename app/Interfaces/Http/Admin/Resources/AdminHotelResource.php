<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

final class AdminHotelResource extends BaseResource
{
    /** @return array<string, mixed> */
    protected function data(Request $request): ?array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'location_id' => data_get($resource, 'location_id'),
            'hotel_type_id' => data_get($resource, 'hotel_type_id'),
            'name' => data_get($resource, 'name'),
            'slug' => data_get($resource, 'slug'),
            'thumbnail' => data_get($resource, 'thumbnail'),
            'star_rating' => data_get($resource, 'star_rating'),
            'address' => data_get($resource, 'address'),
            'ward' => data_get($resource, 'ward'),
            'district' => data_get($resource, 'district'),
            'latitude' => data_get($resource, 'latitude'),
            'longitude' => data_get($resource, 'longitude'),
            'google_map_url' => data_get($resource, 'google_map_url'),
            'description' => data_get($resource, 'description'),
            'price_from' => data_get($resource, 'price_from'),
            'is_free_cancel' => (bool) data_get($resource, 'is_free_cancel', false),
            'is_pay_later' => (bool) data_get($resource, 'is_pay_later', false),
            'is_featured' => (bool) data_get($resource, 'is_featured', false),
            'is_active' => (bool) data_get($resource, 'is_active', true),
            'rating_score' => data_get($resource, 'rating_score'),
            'rating_count' => (int) data_get($resource, 'rating_count', 0),
            'meta_title' => data_get($resource, 'meta_title'),
            'meta_description' => data_get($resource, 'meta_description'),
            'created_at' => data_get($resource, 'created_at'),
            'updated_at' => data_get($resource, 'updated_at'),
            'images' => collect((array) data_get($resource, 'images', []))
                ->map(static fn($item): array => [
                    'id' => data_get($item, 'id'),
                    'url' => data_get($item, 'url'),
                    'alt' => data_get($item, 'alt'),
                    'is_cover' => (bool) data_get($item, 'is_cover', false),
                    'sort_order' => (int) data_get($item, 'sort_order', 0),
                ])
                ->values()
                ->all(),
            'amenities' => collect((array) data_get($resource, 'amenities', []))
                ->map(static fn($item): array => [
                    'id' => data_get($item, 'id'),
                    'name' => data_get($item, 'name'),
                    'icon' => data_get($item, 'icon'),
                    'type' => data_get($item, 'type'),
                ])
                ->values()
                ->all(),
            'location' => data_get($resource, 'location') !== null ? [
                'id' => data_get($resource, 'location.id'),
                'name' => data_get($resource, 'location.name'),
                'slug' => data_get($resource, 'location.slug'),
            ] : null,
            'hotel_type' => data_get($resource, 'hotelType') !== null ? [
                'id' => data_get($resource, 'hotelType.id'),
                'name' => data_get($resource, 'hotelType.name'),
            ] : null,
        ];
    }
}
