<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class HotelTypeModel extends Model
{
    use HasFactory;
    use HasSlug;

    protected $table = 'hotel_types';

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_active',
        'sort',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function hotels(): HasMany
    {
        return $this->hasMany(HotelModel::class);
    }
}
