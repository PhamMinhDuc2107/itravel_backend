<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourPriceOverrideModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'tour_schedule_id',
        'departure_date',
        'adult_price',
        'child_price',
        'infant_price',
        'is_active',
        'note',
    ];

    protected $casts = [
        'departure_date' => 'date',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(TourModel::class);
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(TourScheduleModel::class, 'tour_schedule_id');
    }
}
