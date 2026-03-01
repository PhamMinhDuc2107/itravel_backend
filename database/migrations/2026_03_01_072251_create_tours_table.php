<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete()->comment('Danh mục tour');

            $table->string('code', 50)->unique()->comment('Mã tour');
            $table->string('title', 255)->comment('Tiêu đề tour');
            $table->string('slug', 255)->unique()->comment('Slug URL');
            $table->string('thumbnail', 255)->nullable()->comment('Ảnh đại diện');
            $table->text('description')->nullable()->comment('Mô tả ngắn');

            $table->unsignedTinyInteger('duration_days')->comment('Số ngày');
            $table->unsignedTinyInteger('duration_nights')->comment('Số đêm');
            $table->string('departure_from', 150)->nullable()->comment('Điểm khởi hành');
            $table->string('destination', 255)->nullable()->comment('Điểm đến');

            $table->string('attractions', 500)->nullable()->comment('Điểm tham quan');
            $table->string('cuisine', 500)->nullable()->comment('Ẩm thực');
            $table->string('suitable_for', 255)->nullable()->default('Tất cả')->comment('Phù hợp cho');

            $table->tinyInteger('status')->default(0)->comment('Trạng thái: 0=Bản nháp, 1=Hoạt động, 2=Không hoạt động, 3=Hết chỗ');
            $table->tinyInteger('is_featured')->default(0)->comment('Nổi bật: 0=Không, 1=Có');
            $table->tinyInteger('is_hot')->default(0)->comment('Hot: 0=Không, 1=Có');
            $table->unsignedInteger('view_count')->default(0)->comment('Số lượt xem');

            $table->string('meta_title', 255)->nullable()->comment('Tiêu đề SEO');
            $table->string('meta_description', 500)->nullable()->comment('Mô tả SEO');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người tạo');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người cập nhật');
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index('is_hot');
            $table->index('code');
            $table->index('view_count');
            $table->index('created_by');
            $table->index(['status', 'is_featured']);
            $table->index(['category_id', 'status']);
            $table->index(['is_hot', 'status']);
            $table->fullText(['title', 'description', 'destination']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
