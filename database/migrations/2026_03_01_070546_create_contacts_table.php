<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150)->comment('Họ và tên');
            $table->string('email', 150)->comment('Email liên hệ');
            $table->string('phone', 20)->nullable()->comment('Số điện thoại');
            $table->string('subject', 255)->nullable()->comment('Tiêu đề');
            $table->text('message')->comment('Nội dung liên hệ');
            $table->tinyInteger('status')->default(0)->comment('Trạng thái: 0=Mới, 1=Đang xử lý, 2=Đã giải quyết, 3=Spam');
            $table->text('admin_note')->nullable()->comment('Ghi chú của admin');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->nullOnDelete()->comment('Người xử lý');
            $table->timestamp('resolved_at')->nullable()->comment('Thời gian giải quyết');
            $table->string('ip_address', 45)->nullable()->comment('Địa chỉ IP');
            $table->timestamps();
            $table->softDeletes();

            $table->index('status');
            $table->index('email');
            $table->index('resolved_by');
            $table->index('created_at');
            $table->index(['status', 'created_at']);
            $table->index(['email', 'status']);
            $table->fullText(['full_name', 'subject', 'message']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
