<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class HotelRoomModel extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'hotel_id',
        'name',
        'slug',
        'description',
        'max_adults',
        'max_children',
        'area_sqm',
        'total_rooms',
        'available_rooms',
        'price_per_night',
        'currency',
        'is_free_cancel',
        'is_pay_later',
        'is_active',
    ];

    protected $casts = [
        'area_sqm' => 'decimal:1',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function hotel(): BelongsTo
    {
        return $this->belongsTo(HotelModel::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(HotelRoomImageModel::class);
    }

    public function priceOverrides(): HasMany
    {
        return $this->hasMany(HotelRoomPriceOverrideModel::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(AmenityModel::class, 'hotel_room_amenities');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(HotelReviewModel::class, 'reviewable');
    }
}
