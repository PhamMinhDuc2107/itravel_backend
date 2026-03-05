<?php

declare(strict_types=1);

use App\Interfaces\Http\Admin\Controllers\AdminAuthController;
use App\Interfaces\Http\Admin\Middleware\AdminJwtAuthMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/auth')->group(function (): void {
    Route::post('login', [AdminAuthController::class, 'login']);
    Route::post('refresh', [AdminAuthController::class, 'refresh']);

    Route::middleware(AdminJwtAuthMiddleware::class)->group(function (): void {
        Route::get('me', [AdminAuthController::class, 'me']);
        Route::post('logout', [AdminAuthController::class, 'logout']);
    });
});
