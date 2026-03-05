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
            $table->foreignId('tour_id')->constrained('tours')->cascadeOnDelete()->comment('ModelTour');

            $table->date('departure_date')->comment('NgÃ y khá»Ÿi hÃ nh');
            $table->date('return_date')->nullable()->comment('NgÃ y vá»');

            $table->unsignedSmallInteger('max_slots')->default(0)->comment('Sá»‘ chá»— tá»‘i Ä‘a');
            $table->unsignedSmallInteger('booked_slots')->default(0)->comment('Sá»‘ chá»— Ä‘Ã£ Ä‘áº·t');
            $table->unsignedSmallInteger('available_slots')
                  ->storedAs('max_slots - booked_slots')
                  ->comment('Sá»‘ chá»— cÃ²n trá»‘ng');

            $table->tinyInteger('status')->default(0)->comment('Tráº¡ng thÃ¡i: 0=Má»Ÿ, 1=Äáº§y, 2=Há»§y, 3=ÄÃ£ khá»Ÿi hÃ nh');
            $table->text('note')->nullable()->comment('Ghi chÃº');

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

