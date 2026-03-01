<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_price_overrides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete()->comment('Tour');
            $table->foreignId('tour_schedule_id')->constrained('tour_schedules')->cascadeOnDelete()->comment('Lịch trình tour');

            $table->date('departure_date')->comment('Ngày khởi hành');

            $table->unsignedBigInteger('adult_price')->nullable()->comment('Giá người lớn');
            $table->unsignedBigInteger('child_price')->nullable()->comment('Giá trẻ em');
            $table->unsignedBigInteger('infant_price')->nullable()->comment('Giá em bé');

            $table->tinyInteger('is_active')->default(1)->comment('Trạng thái: 0=Không hoạt động, 1=Hoạt động');
            $table->text('note')->nullable()->comment('Ghi chú');

            $table->timestamps();

            $table->unique(['tour_id', 'departure_date']);
            $table->index('tour_id');
            $table->index('tour_schedule_id');
            $table->index('departure_date');
            $table->index('is_active');
            $table->index(['tour_id', 'is_active']);
            $table->index(['tour_schedule_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_price_overrides');
    }
};
