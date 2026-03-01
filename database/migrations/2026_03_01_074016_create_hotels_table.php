<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained('locations')->restrictOnDelete()->comment('Địa điểm');
            $table->foreignId('hotel_type_id')->constrained('hotel_types')->restrictOnDelete()->comment('Loại khách sạn');

            $table->string('name', 255)->comment('Tên khách sạn');
            $table->string('slug', 255)->unique()->comment('Slug URL');
            $table->string('thumbnail', 255)->nullable()->comment('Ảnh đại diện');
            $table->unsignedTinyInteger('star_rating')->default(0)->comment('Số sao');

            $table->string('address', 500)->nullable()->comment('Địa chỉ');
            $table->string('ward', 150)->nullable()->comment('Phường/Xã');
            $table->string('district', 150)->nullable()->comment('Quận/Huyện');
            $table->decimal('latitude', 10, 7)->nullable()->comment('Vĩ độ');
            $table->decimal('longitude', 10, 7)->nullable()->comment('Kinh độ');
            $table->string('google_map_url', 1000)->nullable()->comment('Link Google Map');

            $table->longText('description')->nullable()->comment('Mô tả chi tiết');

            $table->decimal('price_from', 15, 2)->nullable()->comment('Giá từ');

            $table->tinyInteger('is_free_cancel')->default(0)->comment('Hủy miễn phí: 0=Không, 1=Có');
            $table->tinyInteger('is_pay_later')->default(0)->comment('Thanh toán sau: 0=Không, 1=Có');
            $table->tinyInteger('is_featured')->default(0)->comment('Nổi bật: 0=Không, 1=Có');
            $table->tinyInteger('is_active')->default(1)->comment('Trạng thái: 0=Không hoạt động, 1=Hoạt động');

            $table->decimal('rating_score', 3, 1)->default(0)->comment('Điểm đánh giá');
            $table->unsignedInteger('rating_count')->default(0)->comment('Số lượt đánh giá');

            $table->string('meta_title', 255)->nullable()->comment('Tiêu đề SEO');
            $table->string('meta_description', 500)->nullable()->comment('Mô tả SEO');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người tạo');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người cập nhật');
            $table->timestamps();
            $table->softDeletes();

            $table->index('location_id');
            $table->index('hotel_type_id');
            $table->index('star_rating');
            $table->index('price_from');
            $table->index('rating_score');
            $table->index('is_featured');
            $table->index('is_active');
            $table->index('is_free_cancel');
            $table->index('is_pay_later');
            $table->index('created_by');
            $table->index(['location_id', 'is_active']);
            $table->index(['hotel_type_id', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->index(['location_id', 'star_rating', 'is_active']);
            $table->index(['price_from', 'rating_score']);
            $table->fullText(['name', 'address', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
