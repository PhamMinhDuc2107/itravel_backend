<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

final class AdminHotelListRequest extends BaseAdminRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'page' => ['sometimes', 'integer', 'min:1'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
            'search' => ['sometimes', 'string', 'max:255'],
            'search_by' => ['sometimes', 'string', 'max:50'],
            'location_id' => ['sometimes', 'integer', 'exists:locations,id'],
            'hotel_type_id' => ['sometimes', 'integer', 'exists:hotel_types,id'],
            'is_active' => ['sometimes', 'boolean'],
            'is_featured' => ['sometimes', 'boolean'],
        ];
    }
}
