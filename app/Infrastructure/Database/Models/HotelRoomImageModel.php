<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelRoomImageModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'hotel_room_id',
        'url',
        'alt',
        'is_cover',
        'sort_order',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(HotelRoomModel::class, 'hotel_room_id');
    }
}
