<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminStoreHotelRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'hotel_type_id' => ['required', 'integer', 'exists:hotel_types,id'],
            'name' => ['required', 'string', 'max:255'],
            'thumbnail' => ['nullable', 'string', 'max:255'],
            'thumbnail_file' => ['nullable', 'file', 'image', 'max:5120'],
            'star_rating' => ['nullable', 'integer', 'between:1,5'],
            'address' => ['nullable', 'string', 'max:255'],
            'ward' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'google_map_url' => ['nullable', 'url', 'max:255'],
            'description' => ['nullable', 'string'],
            'price_from' => ['nullable', 'numeric', 'min:0'],
            'is_free_cancel' => ['sometimes', 'boolean'],
            'is_pay_later' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'rating_score' => ['nullable', 'numeric', 'between:0,5'],
            'rating_count' => ['sometimes', 'integer', 'min:0'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],

            'hotel_images' => ['sometimes', 'array'],
            'hotel_images.*.url' => ['nullable', 'string', 'max:255', 'required_without:hotel_images.*.file'],
            'hotel_images.*.file' => ['nullable', 'file', 'image', 'max:5120', 'required_without:hotel_images.*.url'],
            'hotel_images.*.alt' => ['nullable', 'string', 'max:255'],
            'hotel_images.*.is_cover' => ['sometimes', 'boolean'],
            'hotel_images.*.sort_order' => ['sometimes', 'integer', 'min:0', 'max:255'],

            'amenity_ids' => ['sometimes', 'array'],
            'amenity_ids.*' => ['integer', 'exists:amenities,id'],
        ];
    }
}
