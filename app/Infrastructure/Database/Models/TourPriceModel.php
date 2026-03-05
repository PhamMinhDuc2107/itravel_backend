<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourPriceModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'tour_id',
        'passenger_type',
        'price',
        'currency',
        'includes',
        'excludes',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(TourModel::class);
    }
}
