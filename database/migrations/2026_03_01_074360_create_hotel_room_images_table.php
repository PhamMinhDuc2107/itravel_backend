<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_room_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_room_id')->constrained('hotel_rooms')->cascadeOnDelete()->comment('Phòng khách sạn');
            $table->string('url', 255)->comment('Đường dẫn ảnh');
            $table->string('alt', 255)->nullable()->comment('Mô tả ảnh');
            $table->tinyInteger('is_cover')->default(0)->comment('Ảnh bìa: 0=Không, 1=Có');
            $table->unsignedTinyInteger('sort_order')->default(0)->comment('Thứ tự sắp xếp');
            $table->timestamps();

            $table->index('hotel_room_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_room_images');
    }
};
