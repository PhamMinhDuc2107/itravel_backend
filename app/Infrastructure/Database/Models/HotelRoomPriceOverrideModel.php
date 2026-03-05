<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelRoomPriceOverrideModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_room_id',
        'date_from',
        'date_to',
        'price_per_night',
        'currency',
        'note',
        'is_active',
    ];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(HotelRoomModel::class, 'hotel_room_id');
    }
}
