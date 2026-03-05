<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class NewsCategoryModel extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    protected $table = 'news_categories';

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'thumbnail',
        'description',
        'is_active',
        'sort',
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

    public function news(): HasMany
    {
        return $this->hasMany(NewsModel::class, 'news_category_id');
    }
}
