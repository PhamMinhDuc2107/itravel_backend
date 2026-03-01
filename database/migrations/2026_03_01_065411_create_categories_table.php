<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('categories')
                  ->cascadeOnDelete();

            $table->string('name', 150);
            $table->string('slug', 150)->unique();
            $table->string('type', 50);        
            $table->text('description')->nullable();

            $table->unsignedTinyInteger('sort')->default(0);
            $table->tinyInteger('is_active')->default(1)->comment('Trạng thái: 0=Không hoạt động, 1=Hoạt động');
            $table->tinyInteger('is_featured')->default(0)->comment('Nổi bật: 0=Không, 1=Có');

            $table->string('meta_title', 255)->nullable();
            $table->string('meta_description', 500)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
            $table->index('type');
            $table->index('sort');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index(['type', 'is_active']);
            $table->index(['parent_id', 'is_active']);
            $table->fullText(['name', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
