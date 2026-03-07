<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class TourModel extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    public const SEARCHABLE_COLUMNS = [
        'code',
        'title',
        'slug',
        'description',
        'destination',
        'attractions',
    ];

    protected $fillable = [
        'category_id',
        'code',
        'title',
        'slug',
        'thumbnail',
        'description',
        'duration_days',
        'duration_nights',
        'departure_from',
        'destination',
        'attractions',
        'cuisine',
        'suitable_for',
        'status',
        'is_featured',
        'is_hot',
        'view_count',
        'meta_title',
        'meta_description',
        'created_by',
        'updated_by',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(CategoryModel::class);
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
        return $this->hasMany(TourImageModel::class);
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(TourItineraryModel::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(TourNoteModel::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(TourPriceModel::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(TourScheduleModel::class);
    }

    public function priceOverrides(): HasMany
    {
        return $this->hasMany(TourPriceOverrideModel::class);
    }

    public function tourLocations(): HasMany
    {
        return $this->hasMany(TourLocationModel::class);
    }

    public function locations(): BelongsToMany
    {
        return $this->belongsToMany(LocationModel::class, 'tour_locations')
            ->withPivot(['role', 'sort']);
    }

    /**
     * @return array<int, string>
     */
    public static function searchableColumns(): array
    {
        return self::SEARCHABLE_COLUMNS;
    }
}
