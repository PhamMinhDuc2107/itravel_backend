<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_itineraries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->unsignedTinyInteger('day_number');
            $table->string('title', 255);
            $table->longText('content')->nullable();
            $table->timestamps();
        
            $table->unique(['tour_id', 'day_number']);
            $table->index('tour_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_itineraries');
    }
};
