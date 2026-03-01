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
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete()->comment('Tour');

            $table->tinyInteger('passenger_type')->comment('Loại hành khách: 0=Người lớn, 1=Trẻ em, 2=Em bé');

            $table->unsignedBigInteger('price')->comment('Giá tiền');
            $table->string('currency', 10)->default('VND')->comment('Đơn vị tiền tệ');
            $table->text('includes')->nullable()->comment('Đã bao gồm');
            $table->text('excludes')->nullable()->comment('Chưa bao gồm');

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
