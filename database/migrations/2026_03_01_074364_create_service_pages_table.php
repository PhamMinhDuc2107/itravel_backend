<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete()->comment('Danh mục');

            $table->string('name', 255)->comment('Tên dịch vụ');
            $table->string('slug', 255)->unique()->comment('Slug URL');
            $table->string('thumbnail', 255)->nullable()->comment('Ảnh đại diện');
            $table->text('excerpt')->nullable()->comment('Tóm tắt ngắn');
            $table->longText('content')->comment('Nội dung chi tiết');

            $table->tinyInteger('is_featured')->default(0)->comment('Nổi bật: 0=Không, 1=Có');
            $table->tinyInteger('is_active')->default(1)->comment('Trạng thái: 0=Không hoạt động, 1=Hoạt động');
            $table->unsignedTinyInteger('sort_order')->default(0)->comment('Thứ tự sắp xếp');

            $table->string('meta_title', 255)->nullable()->comment('Tiêu đề SEO');
            $table->string('meta_description', 500)->nullable()->comment('Mô tả SEO');

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người tạo');
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người cập nhật');
            $table->timestamps();
            $table->softDeletes();

            $table->index('category_id');
            $table->index('is_active');
            $table->index('is_featured');
            $table->index('created_by');
            $table->index(['category_id', 'is_active']);
            $table->index(['is_featured', 'is_active']);
            $table->fullText(['name', 'excerpt', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_pages');
    }
};
