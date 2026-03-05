<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete()->comment('ModelTour');

            $table->tinyInteger('passenger_type')->comment('Loáº¡i hÃ nh khÃ¡ch: 0=NgÆ°á»i lá»›n, 1=Tráº» em, 2=Em bÃ©');

            $table->unsignedBigInteger('price')->comment('GiÃ¡ tiá»n');
            $table->string('currency', 10)->default('VND')->comment('ÄÆ¡n vá»‹ tiá»n tá»‡');
            $table->text('includes')->nullable()->comment('ÄÃ£ bao gá»“m');
            $table->text('excludes')->nullable()->comment('ChÆ°a bao gá»“m');

            $table->timestamps();

            $table->unique(['tour_id', 'passenger_type']);
            $table->index('tour_id');
            $table->index('passenger_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_prices');
    }
};

