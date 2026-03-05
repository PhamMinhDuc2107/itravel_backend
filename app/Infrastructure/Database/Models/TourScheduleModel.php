<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourScheduleModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'departure_date',
        'return_date',
        'max_slots',
        'booked_slots',
        'status',
        'note',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(TourModel::class);
    }
}
