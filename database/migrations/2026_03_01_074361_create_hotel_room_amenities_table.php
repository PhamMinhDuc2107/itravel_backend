<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_room_amenities', function (Blueprint $table) {
            $table->foreignId('hotel_room_id')->constrained('hotel_rooms')->cascadeOnDelete()->comment('Phòng khách sạn');
            $table->foreignId('amenity_id')->constrained('amenities')->cascadeOnDelete()->comment('Tiện nghi');
            $table->primary(['hotel_room_id', 'amenity_id']);
            $table->index('amenity_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_room_amenities');
    }
};
