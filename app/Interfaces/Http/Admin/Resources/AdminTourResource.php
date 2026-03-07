<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Resources;

use App\Interfaces\Http\Common\Resources\BaseResource;
use Illuminate\Http\Request;

final class AdminTourResource extends BaseResource
{
    /** @return array<string, mixed> */
    protected function data(Request $request): ?array
    {
        $resource = $this->resource;

        return [
            'id' => data_get($resource, 'id'),
            'category_id' => data_get($resource, 'category_id'),
            'code' => data_get($resource, 'code'),
            'title' => data_get($resource, 'title'),
            'slug' => data_get($resource, 'slug'),
            'thumbnail' => data_get($resource, 'thumbnail'),
            'description' => data_get($resource, 'description'),
            'duration_days' => data_get($resource, 'duration_days'),
            'duration_nights' => data_get($resource, 'duration_nights'),
            'departure_from' => data_get($resource, 'departure_from'),
            'destination' => data_get($resource, 'destination'),
            'attractions' => data_get($resource, 'attractions'),
            'cuisine' => data_get($resource, 'cuisine'),
            'suitable_for' => data_get($resource, 'suitable_for'),
            'status' => data_get($resource, 'status'),
            'is_featured' => (bool) data_get($resource, 'is_featured', false),
            'is_hot' => (bool) data_get($resource, 'is_hot', false),
            'view_count' => (int) data_get($resource, 'view_count', 0),
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
                    'sort' => (int) data_get($item, 'sort', 0),
                ])
                ->values()
                ->all(),
            'itineraries' => collect((array) data_get($resource, 'itineraries', []))
                ->map(static fn($item): array => [
                    'id' => data_get($item, 'id'),
                    'day_number' => (int) data_get($item, 'day_number', 0),
                    'title' => data_get($item, 'title'),
                    'content' => data_get($item, 'content'),
                ])
                ->values()
                ->all(),
            'notes' => collect((array) data_get($resource, 'notes', []))
                ->map(static fn($item): array => [
                    'id' => data_get($item, 'id'),
                    'title' => data_get($item, 'title'),
                    'content' => data_get($item, 'content'),
                    'sort' => (int) data_get($item, 'sort', 0),
                ])
                ->values()
                ->all(),
            'prices' => collect((array) data_get($resource, 'prices', []))
                ->map(static fn($item): array => [
                    'id' => data_get($item, 'id'),
                    'passenger_type' => (int) data_get($item, 'passenger_type', 0),
                    'price' => data_get($item, 'price'),
                    'currency' => data_get($item, 'currency'),
                    'includes' => data_get($item, 'includes'),
                    'excludes' => data_get($item, 'excludes'),
                ])
                ->values()
                ->all(),
            'schedules' => collect((array) data_get($resource, 'schedules', []))
                ->map(static fn($item): array => [
                    'id' => data_get($item, 'id'),
                    'departure_date' => data_get($item, 'departure_date'),
                    'return_date' => data_get($item, 'return_date'),
                    'max_slots' => (int) data_get($item, 'max_slots', 0),
                    'booked_slots' => (int) data_get($item, 'booked_slots', 0),
                    'status' => (int) data_get($item, 'status', 0),
                    'note' => data_get($item, 'note'),
                ])
                ->values()
                ->all(),
            'price_overrides' => collect((array) data_get($resource, 'price_overrides', data_get($resource, 'priceOverrides', [])))
                ->map(static fn($item): array => [
                    'id' => data_get($item, 'id'),
                    'tour_schedule_id' => data_get($item, 'tour_schedule_id'),
                    'departure_date' => data_get($item, 'departure_date'),
                    'adult_price' => data_get($item, 'adult_price'),
                    'child_price' => data_get($item, 'child_price'),
                    'infant_price' => data_get($item, 'infant_price'),
                    'is_active' => (bool) data_get($item, 'is_active', true),
                    'note' => data_get($item, 'note'),
                ])
                ->values()
                ->all(),
            'tour_locations' => collect((array) data_get($resource, 'tour_locations', data_get($resource, 'tourLocations', [])))
                ->map(static fn($item): array => [
                    'id' => data_get($item, 'id'),
                    'location_id' => data_get($item, 'location_id'),
                    'role' => (int) data_get($item, 'role', 1),
                    'sort' => (int) data_get($item, 'sort', 0),
                    'location' => data_get($item, 'location') !== null ? [
                        'id' => data_get($item, 'location.id'),
                        'name' => data_get($item, 'location.name'),
                        'slug' => data_get($item, 'location.slug'),
                    ] : null,
                ])
                ->values()
                ->all(),
            'category' => data_get($resource, 'category') !== null ? [
                'id' => data_get($resource, 'category.id'),
                'name' => data_get($resource, 'category.name'),
                'slug' => data_get($resource, 'category.slug'),
            ] : null,
            'locations' => collect((array) data_get($resource, 'locations', []))
                ->map(static fn($item): array => [
                    'id' => data_get($item, 'id'),
                    'name' => data_get($item, 'name'),
                    'slug' => data_get($item, 'slug'),
                ])
                ->values()
                ->all(),
        ];
    }
}
