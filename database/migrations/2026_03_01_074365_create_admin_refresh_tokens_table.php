<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_refresh_tokens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete()->comment('Admin');

            $table->string('token', 64)->unique()->comment('Refresh Token (đã băm)');

            $table->string('user_agent')->nullable()->comment('Thông tin trình duyệt');
            $table->string('ip_address', 45)->nullable()->comment('Địa chỉ IP');

            $table->tinyInteger('is_revoked')->default(0)->comment('Thu hồi: 0=Không, 1=Có');

            $table->timestamp('expires_at')->comment('Thời gian hết hạn');

            $table->timestamps();

            $table->index(['admin_id', 'is_revoked']);
            $table->index('expires_at');
            $table->index(['admin_id', 'expires_at']);
            $table->index(['token', 'is_revoked', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_refresh_tokens');
    }
};
