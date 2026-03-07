<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminUpdateTourRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'code' => ['nullable', 'string', 'max:100'],
            'title' => ['required', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'thumbnail_file' => ['nullable', 'file', 'image', 'max:5120'],
            'description' => ['nullable', 'string'],
            'duration_days' => ['nullable', 'integer', 'min:0'],
            'duration_nights' => ['nullable', 'integer', 'min:0'],
            'departure_from' => ['nullable', 'string', 'max:255'],
            'destination' => ['nullable', 'string', 'max:255'],
            'attractions' => ['nullable', 'string'],
            'cuisine' => ['nullable', 'string'],
            'suitable_for' => ['nullable', 'string'],
            'status' => ['sometimes', 'string', 'max:50'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_hot' => ['sometimes', 'boolean'],
            'view_count' => ['sometimes', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],

            'tour_images' => ['sometimes', 'array'],
            'tour_images.*.url' => ['nullable', 'string', 'max:255', 'required_without:tour_images.*.file'],
            'tour_images.*.file' => ['nullable', 'file', 'image', 'max:5120', 'required_without:tour_images.*.url'],
            'tour_images.*.alt' => ['nullable', 'string', 'max:255'],
            'tour_images.*.is_cover' => ['sometimes', 'boolean'],
            'tour_images.*.sort' => ['sometimes', 'integer', 'min:0', 'max:255'],

            'tour_price_overrides' => ['sometimes', 'array'],
            'tour_price_overrides.*.tour_schedule_id' => ['required', 'integer', 'exists:tour_schedules,id'],
            'tour_price_overrides.*.departure_date' => ['required', 'date'],
            'tour_price_overrides.*.adult_price' => ['nullable', 'integer', 'min:0'],
            'tour_price_overrides.*.child_price' => ['nullable', 'integer', 'min:0'],
            'tour_price_overrides.*.infant_price' => ['nullable', 'integer', 'min:0'],
            'tour_price_overrides.*.is_active' => ['sometimes', 'boolean'],
            'tour_price_overrides.*.note' => ['nullable', 'string'],

            // Backward-compatible alias used by some clients.
            'tour_overrides' => ['sometimes', 'array'],
            'tour_overrides.*.tour_schedule_id' => ['required', 'integer', 'exists:tour_schedules,id'],
            'tour_overrides.*.departure_date' => ['required', 'date'],
            'tour_overrides.*.adult_price' => ['nullable', 'integer', 'min:0'],
            'tour_overrides.*.child_price' => ['nullable', 'integer', 'min:0'],
            'tour_overrides.*.infant_price' => ['nullable', 'integer', 'min:0'],
            'tour_overrides.*.is_active' => ['sometimes', 'boolean'],
            'tour_overrides.*.note' => ['nullable', 'string'],

            'tour_itineraries' => ['sometimes', 'array'],
            'tour_itineraries.*.day_number' => ['required', 'integer', 'min:1'],
            'tour_itineraries.*.title' => ['required', 'string', 'max:255'],
            'tour_itineraries.*.content' => ['nullable', 'string'],

            'tour_notes' => ['sometimes', 'array'],
            'tour_notes.*.title' => ['required', 'string', 'max:255'],
            'tour_notes.*.content' => ['nullable', 'string'],
            'tour_notes.*.sort' => ['sometimes', 'integer', 'min:0', 'max:255'],

            'tour_prices' => ['sometimes', 'array'],
            'tour_prices.*.passenger_type' => ['required', 'integer', 'between:0,2'],
            'tour_prices.*.price' => ['required', 'integer', 'min:0'],
            'tour_prices.*.currency' => ['sometimes', 'string', 'max:10'],
            'tour_prices.*.includes' => ['nullable', 'string'],
            'tour_prices.*.excludes' => ['nullable', 'string'],

            'tour_schedules' => ['sometimes', 'array'],
            'tour_schedules.*.departure_date' => ['required', 'date'],
            'tour_schedules.*.return_date' => ['nullable', 'date'],
            'tour_schedules.*.max_slots' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'tour_schedules.*.booked_slots' => ['sometimes', 'integer', 'min:0', 'max:65535'],
            'tour_schedules.*.status' => ['sometimes', 'integer', 'between:0,3'],
            'tour_schedules.*.note' => ['nullable', 'string'],

            'tour_locations' => ['sometimes', 'array'],
            'tour_locations.*.location_id' => ['required', 'integer', 'exists:locations,id'],
            'tour_locations.*.role' => ['sometimes', 'integer', 'between:0,2'],
            'tour_locations.*.sort' => ['sometimes', 'integer', 'min:0', 'max:255'],
        ];
    }
}
