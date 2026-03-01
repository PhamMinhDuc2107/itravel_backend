<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete();
            $table->string('title', 255);
            $table->longText('content')->nullable();
            $table->unsignedTinyInteger('sort')->default(0);
            $table->timestamps();

            $table->index('tour_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tour_notes');
    }
};
