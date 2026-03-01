<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete()->comment('Tour');
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete()->comment('Địa điểm');
            $table->tinyInteger('role')->default(1)->comment('Vai trò: 0=Điểm khởi hành, 1=Điểm đến, 2=Điểm trung chuyển');
            $table->unsignedTinyInteger('sort')->default(0)->comment('Thứ tự sắp xếp');

            $table->unique(['tour_id', 'location_id', 'role']);
            $table->index('tour_id');
            $table->index('location_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_locations');
    }
};
