<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class NewsModel extends Model
{
    use HasFactory;
    use HasSlug;
    use SoftDeletes;

    public const SEARCHABLE_COLUMNS = [
        'title',
        'slug',
        'excerpt',
        'content',
    ];

    protected $table = 'news';

    protected $fillable = [
        'news_category_id',
        'author_id',
        'title',
        'slug',
        'thumbnail',
        'excerpt',
        'content',
        'status',
        'is_featured',
        'view_count',
        'meta_title',
        'meta_description',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(NewsCategoryModel::class, 'news_category_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'author_id');
    }

    /**
     * @return array<int, string>
     */
    public static function searchableColumns(): array
    {
        return self::SEARCHABLE_COLUMNS;
    }
}
