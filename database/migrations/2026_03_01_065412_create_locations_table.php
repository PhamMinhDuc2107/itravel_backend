<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')
                  ->nullable()
                  ->constrained('locations')
                  ->cascadeOnDelete()
                  ->comment('NULL = Quốc gia, có giá trị = Tỉnh/TP thuộc quốc gia đó');

            $table->string('name', 150)->comment('Tên địa điểm');
            $table->string('slug', 150)->unique()->comment('Slug URL thân thiện');

            $table->enum('type', ['country', 'province', 'city', 'district', 'area'])
                  ->default('country')
                  ->comment('Loại: country=Quốc gia, province=Tỉnh, city=Thành phố, district=Quận/Huyện, area=Khu vực');

            $table->string('code', 10)->nullable()->comment('Mã ISO quốc gia (JP, KR, VN) hoặc mã tỉnh (DN, CB, QN)');

            $table->string('thumbnail', 255)->nullable()->comment('Ảnh hiển thị trong grid');
            $table->string('banner', 255)->nullable()->comment('Ảnh banner trang detail');
            $table->text('description')->nullable()->comment('Mô tả ngắn về địa điểm');

            $table->tinyInteger('is_active')->default(1)->comment('Trạng thái: 0=Không hoạt động, 1=Hoạt động');
            $table->tinyInteger('is_featured')->default(0)->comment('Nổi bật: 0=Không, 1=Có');
            $table->tinyInteger('is_domestic')->default(1)->comment('Loại: 0=Nước ngoài, 1=Trong nước');
            $table->unsignedTinyInteger('sort_order')->default(0)->comment('Thứ tự sắp xếp');

            $table->decimal('latitude', 10, 7)->nullable()->comment('Vĩ độ (dùng cho map)');
            $table->decimal('longitude', 10, 7)->nullable()->comment('Kinh độ (dùng cho map)');

            $table->string('meta_title', 255)->nullable()->comment('Tiêu đề SEO');
            $table->string('meta_description', 500)->nullable()->comment('Mô tả SEO');

            $table->timestamps();
            $table->softDeletes();

            $table->index('parent_id');
            $table->index('type');
            $table->index('is_featured');
            $table->index('is_domestic');
            $table->index('is_active');
            $table->index('sort_order');
            $table->index('code');
            $table->index(['type', 'is_active']);
            $table->index(['is_domestic', 'is_active']);
            $table->index(['parent_id', 'type']);
            $table->fullText(['name', 'description']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
