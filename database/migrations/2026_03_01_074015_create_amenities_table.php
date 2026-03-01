<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150)->comment('Tên tiện nghi');
            $table->string('icon', 255)->nullable()->comment('Icon');
            $table->tinyInteger('type')->default(2)->comment('Loại: 0=Khách sạn, 1=Phòng, 2=Cả hai');
            $table->tinyInteger('is_active')->default(1)->comment('Trạng thái: 0=Không hoạt động, 1=Hoạt động');
            $table->unsignedTinyInteger('sort_order')->default(0)->comment('Thứ tự sắp xếp');
            $table->timestamps();

            $table->index('type');
            $table->index('is_active');
            $table->index(['type', 'is_active']);
            $table->fullText(['name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('amenities');
    }
};
