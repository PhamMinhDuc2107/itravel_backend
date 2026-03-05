<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TourLocationModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'tour_id',
        'location_id',
        'role',
        'sort',
    ];

    public function tour(): BelongsTo
    {
        return $this->belongsTo(TourModel::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(LocationModel::class);
    }
}
