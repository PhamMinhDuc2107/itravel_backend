<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotel_reviews', function (Blueprint $table) {
            $table->id();
            $table->morphs('reviewable');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->comment('Người đánh giá');

            $table->string('reviewer_name', 150)->nullable()->comment('Tên người đánh giá');
            $table->tinyInteger('travel_type')->nullable()->comment('Loại du lịch: 0=Một mình, 1=Cặp đôi, 2=Gia đình, 3=Công tác, 4=Bạn bè, 5=Khác');
            $table->unsignedTinyInteger('nights_stayed')->nullable()->comment('Số đêm lưu trú');

            $table->decimal('score_location', 3, 1)->default(0)->comment('Điểm vị trí');
            $table->decimal('score_price', 3, 1)->default(0)->comment('Điểm giá cả');
            $table->decimal('score_service', 3, 1)->default(0)->comment('Điểm dịch vụ');
            $table->decimal('score_cleanliness', 3, 1)->default(0)->comment('Điểm vệ sinh');
            $table->decimal('score_amenities', 3, 1)->default(0)->comment('Điểm tiện nghi');
            $table->decimal('score_total', 3, 1)->default(0)->comment('Điểm tổng');

            $table->text('content')->nullable()->comment('Nội dung đánh giá');
            $table->tinyInteger('status')->default(0)->comment('Trạng thái: 0=Chờ duyệt, 1=Đã duyệt, 2=Từ chối');

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người duyệt');
            $table->timestamp('approved_at')->nullable()->comment('Thời gian duyệt');

            $table->timestamps();
            $table->softDeletes();

            $table->index('reviewable_type');
            $table->index('reviewable_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('score_total');
            $table->index('travel_type');
            $table->index('created_at');
            $table->index(['reviewable_type', 'reviewable_id', 'status']);
            $table->index(['status', 'score_total']);
            $table->index(['user_id', 'status']);
            $table->fullText(['content']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
