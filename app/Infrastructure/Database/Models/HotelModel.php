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

class HotelModel extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'location_id',
        'hotel_type_id',
        'name',
        'slug',
        'thumbnail',
        'star_rating',
        'address',
        'ward',
        'district',
        'latitude',
        'longitude',
        'google_map_url',
        'description',
        'price_from',
        'is_free_cancel',
        'is_pay_later',
        'is_featured',
        'is_active',
        'rating_score',
        'rating_count',
        'meta_title',
        'meta_description',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'price_from' => 'decimal:2',
        'rating_score' => 'decimal:1',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(LocationModel::class);
    }

    public function hotelType(): BelongsTo
    {
        return $this->belongsTo(HotelTypeModel::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'updated_by');
    }

    public function images(): HasMany
    {
        return $this->hasMany(HotelImageModel::class);
    }

    public function rooms(): HasMany
    {
        return $this->hasMany(HotelRoomModel::class);
    }

    public function amenities(): BelongsToMany
    {
        return $this->belongsToMany(AmenityModel::class, 'hotel_amenities');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(HotelReviewModel::class, 'reviewable');
    }
}
