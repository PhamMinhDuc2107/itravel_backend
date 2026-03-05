<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HotelReviewModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'hotel_reviews';

    protected $fillable = [
        'reviewable_type',
        'reviewable_id',
        'user_id',
        'reviewer_name',
        'travel_type',
        'nights_stayed',
        'score_location',
        'score_price',
        'score_service',
        'score_cleanliness',
        'score_amenities',
        'score_total',
        'content',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'score_location' => 'decimal:1',
        'score_price' => 'decimal:1',
        'score_service' => 'decimal:1',
        'score_cleanliness' => 'decimal:1',
        'score_amenities' => 'decimal:1',
        'score_total' => 'decimal:1',
    ];

    public function reviewable(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'approved_by');
    }
}
