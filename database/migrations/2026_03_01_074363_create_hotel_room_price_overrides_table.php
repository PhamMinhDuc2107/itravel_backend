<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_room_price_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_room_id')->constrained('hotel_rooms')->cascadeOnDelete()->comment('Phòng khách sạn');

            $table->date('date_from')->comment('Ngày bắt đầu');
            $table->date('date_to')->comment('Ngày kết thúc');

            $table->unsignedBigInteger('price_per_night')->comment('Giá mỗi đêm');
            $table->string('currency', 10)->default('VND')->comment('Đơn vị tiền tệ');

            $table->text('note')->nullable()->comment('Ghi chú');
            $table->tinyInteger('is_active')->default(1)->comment('Trạng thái: 0=Không hoạt động, 1=Hoạt động');

            $table->timestamps();

            $table->index('hotel_room_id');
            $table->index('date_from');
            $table->index('date_to');
            $table->index('is_active');
            $table->index(['hotel_room_id', 'date_from', 'date_to']);
            $table->index(['hotel_room_id', 'is_active']);
            $table->index(['date_from', 'date_to', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_room_price_overrides');
    }
};
