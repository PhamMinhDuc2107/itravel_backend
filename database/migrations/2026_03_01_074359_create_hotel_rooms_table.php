<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')->cascadeOnDelete()->comment('Khách sạn');

            $table->string('name', 255)->comment('Tên phòng');
            $table->string('slug', 255)->nullable()->comment('Slug URL');
            $table->text('description')->nullable()->comment('Mô tả');

            $table->unsignedTinyInteger('max_adults')->default(2)->comment('Số người lớn tối đa');
            $table->unsignedTinyInteger('max_children')->default(0)->comment('Số trẻ em tối đa');
            $table->decimal('area_sqm', 6, 1)->nullable()->comment('Diện tích (m²)');

            $table->unsignedSmallInteger('total_rooms')->default(0)->comment('Tổng số phòng');
            $table->unsignedSmallInteger('available_rooms')->default(0)->comment('Số phòng còn trống');

            $table->unsignedBigInteger('price_per_night')->comment('Giá mỗi đêm');
            $table->string('currency', 10)->default('VND')->comment('Đơn vị tiền tệ');

            $table->tinyInteger('is_free_cancel')->default(0)->comment('Hủy miễn phí: 0=Không, 1=Có');
            $table->tinyInteger('is_pay_later')->default(0)->comment('Thanh toán sau: 0=Không, 1=Có');
            $table->tinyInteger('is_active')->default(1)->comment('Trạng thái: 0=Không hoạt động, 1=Hoạt động');

            $table->timestamps();
            $table->softDeletes();

            $table->index('hotel_id');
            $table->index('price_per_night');
            $table->index('is_active');
            $table->index('is_free_cancel');
            $table->index('is_pay_later');
            $table->index(['hotel_id', 'is_active']);
            $table->index(['hotel_id', 'price_per_night']);
            $table->index(['is_active', 'price_per_night']);
            $table->fullText(['name', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotel_rooms');
    }
};
