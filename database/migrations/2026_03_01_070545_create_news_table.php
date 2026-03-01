<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('news_category_id')->constrained('news_categories')->restrictOnDelete()->comment('Danh mục tin tức');
            $table->foreignId('author_id')->constrained('users')->restrictOnDelete()->comment('Tác giả');
            $table->string('title', 255)->comment('Tiêu đề');
            $table->string('slug', 255)->unique()->comment('Slug URL');
            $table->string('thumbnail', 255)->nullable()->comment('Ảnh đại diện');
            $table->text('excerpt')->nullable()->comment('Tóm tắt ngắn');
            $table->longText('content')->comment('Nội dung chi tiết');
            $table->tinyInteger('status')->default(0)->comment('Trạng thái: 0=Bản nháp, 1=Đã xuất bản, 2=Lưu trữ');
            $table->tinyInteger('is_featured')->default(0)->comment('Nổi bật: 0=Không, 1=Có');
            $table->unsignedBigInteger('view_count')->default(0)->comment('Số lượt xem');
            $table->string('meta_title', 255)->nullable()->comment('Tiêu đề SEO');
            $table->string('meta_description', 500)->nullable()->comment('Mô tả SEO');
            $table->timestamp('published_at')->nullable()->comment('Thời gian xuất bản');
            $table->timestamps();
            $table->softDeletes();

            $table->index('news_category_id');
            $table->index('author_id');
            $table->index('status');
            $table->index('is_featured');
            $table->index('published_at');
            $table->index('view_count');
            $table->index(['status', 'published_at']);
            $table->index(['news_category_id', 'status']);
            $table->index(['is_featured', 'status']);
            $table->fullText(['title', 'excerpt', 'content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
