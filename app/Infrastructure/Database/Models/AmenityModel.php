<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AmenityModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'type',
        'is_active',
        'sort_order',
    ];

    public function hotels(): BelongsToMany
    {
        return $this->belongsToMany(HotelModel::class, 'hotel_amenities');
    }

    public function hotelRooms(): BelongsToMany
    {
        return $this->belongsToMany(HotelRoomModel::class, 'hotel_room_amenities');
    }
}
