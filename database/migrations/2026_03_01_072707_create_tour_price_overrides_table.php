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
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete()->comment('ModelTour');
            $table->foreignId('tour_schedule_id')->constrained('tour_schedules')->cascadeOnDelete()->comment('Lá»‹ch trÃ¬nh tour');

            $table->date('departure_date')->comment('NgÃ y khá»Ÿi hÃ nh');

            $table->unsignedBigInteger('adult_price')->nullable()->comment('GiÃ¡ ngÆ°á»i lá»›n');
            $table->unsignedBigInteger('child_price')->nullable()->comment('GiÃ¡ tráº» em');
            $table->unsignedBigInteger('infant_price')->nullable()->comment('GiÃ¡ em bÃ©');

            $table->tinyInteger('is_active')->default(1)->comment('Tráº¡ng thÃ¡i: 0=KhÃ´ng hoáº¡t Ä‘á»™ng, 1=Hoáº¡t Ä‘á»™ng');
            $table->text('note')->nullable()->comment('Ghi chÃº');

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

