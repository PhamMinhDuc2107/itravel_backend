<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete()->comment('Tour');

            $table->date('departure_date')->comment('Ngày khởi hành');
            $table->date('return_date')->nullable()->comment('Ngày về');

            $table->unsignedSmallInteger('max_slots')->default(0)->comment('Số chỗ tối đa');
            $table->unsignedSmallInteger('booked_slots')->default(0)->comment('Số chỗ đã đặt');
            $table->unsignedSmallInteger('available_slots')
                  ->storedAs('max_slots - booked_slots')
                  ->comment('Số chỗ còn trống');

            $table->tinyInteger('status')->default(0)->comment('Trạng thái: 0=Mở, 1=Đầy, 2=Hủy, 3=Đã khởi hành');
            $table->text('note')->nullable()->comment('Ghi chú');

            $table->timestamps();

            $table->unique(['tour_id', 'departure_date']);
            $table->index('tour_id');
            $table->index('departure_date');
            $table->index('return_date');
            $table->index('status');
            $table->index(['tour_id', 'status']);
            $table->index(['departure_date', 'status']);
            $table->index(['tour_id', 'departure_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_schedules');
    }
};
