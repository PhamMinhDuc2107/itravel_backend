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

class LocationModel extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'type',
        'code',
        'thumbnail',
        'banner',
        'description',
        'is_active',
        'is_featured',
        'is_domestic',
        'sort_order',
        'latitude',
        'longitude',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function hotels(): HasMany
    {
        return $this->hasMany(HotelModel::class);
    }

    public function tours(): BelongsToMany
    {
        return $this->belongsToMany(TourModel::class, 'tour_locations')
            ->withPivot(['role', 'sort']);
    }
}
