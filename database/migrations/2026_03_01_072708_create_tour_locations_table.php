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
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete()->comment('ModelTour');
            $table->foreignId('location_id')->constrained('locations')->cascadeOnDelete()->comment('Äá»‹a Ä‘iá»ƒm');
            $table->tinyInteger('role')->default(1)->comment('Vai trÃ²: 0=Äiá»ƒm khá»Ÿi hÃ nh, 1=Äiá»ƒm Ä‘áº¿n, 2=Äiá»ƒm trung chuyá»ƒn');
            $table->unsignedTinyInteger('sort')->default(0)->comment('Thá»© tá»± sáº¯p xáº¿p');

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

